<?php

namespace App\Http\Controllers;


use App\Models\Address;
use App\Models\Cart;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showCheckout(Request $request)
    {
        $user = auth()->user();

        // Get user's addresses
        $addresses = Address::where('user_id', $user->id)
            ->orderBy('is_primary', 'desc')
            ->get();

        // If no addresses exist, create default one
        if ($addresses->isEmpty()) {
            $defaultAddress = Address::create([
                'user_id' => $user->id,
                'label' => 'Alamat',
                'receiver_name' => $user->name,
                'phone_number' => $user->telepon,
                'full_address' => $user->alamat,
                'is_primary' => true
            ]);
            $addresses = collect([$defaultAddress]);
        }

        // Ambil data checkout dari session storage yang disimpan di cart
        $selectedItems = json_decode($request->input('selected_items', '[]'), true);

        // Jika tidak ada items yang dipilih, cek session storage
        if (empty($selectedItems)) {
            // Redirect ke cart dengan pesan error
            return redirect()->route('cart.index')
                ->with('error', 'Silakan pilih produk yang akan dicheckout');
        }

        // Ambil data cart items berdasarkan ID yang dipilih
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', array_column($selectedItems, 'id'))
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Produk yang dipilih tidak ditemukan');
        }

        // Hitung total
        $totalPrice = 0;
        $totalDiscount = 0;

        foreach ($cartItems as $item) {
            $price = $item->product->harga * $item->quantity;
            $discount = ($price * $item->product->diskon) / 100;

            $totalPrice += $price;
            $totalDiscount += $discount;
        }

        $grandTotal = $totalPrice - $totalDiscount;

        return view('home.checkout', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'totalDiscount' => $totalDiscount,
            'grandTotal' => $grandTotal,
            'isFromCart' => true,
            'addresses' => $addresses
        ]);
    }

    public function payNow(Request $request)
    {
        $user = auth()->user();
        $items = json_decode($request->query('items'), true);

        // Validasi items tidak kosong
        if (!$items || !is_array($items)) {
            return redirect()->back()->with('error', 'Data produk tidak valid');
        }

        // Get user's addresses
        $addresses = Address::where('user_id', $user->id)
            ->orderBy('is_primary', 'desc')
            ->get();


        // Create default address if none exists
        if ($addresses->isEmpty()) {
            $defaultAddress = Address::create([
                'user_id' => $user->id, // Hubungkan dengan ID user
                'label' => 'Alamat',
                'receiver_name' => $user->name ?? '',
                'phone_number' => $user->telepon ?? '',
                'full_address' => $user->alamat ?? '',
                'is_primary' => true
            ]);

            // Konversi ke collection agar tetap konsisten dengan alur sebelumnya
            $addresses = collect([$defaultAddress]);
        }

        try {
            $directItems = collect($items)->map(function ($item) {
                if (!isset($item['id']) || !isset($item['quantity'])) {
                    throw new \Exception('Data produk tidak lengkap');
                }

                $produk = Produk::findOrFail($item['id']);
                return [
                    'produk_id' => $produk->id,
                    'product' => $produk,
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $produk->harga ?? 0,
                    'discount' => $produk->diskon ?? 0,
                    'subtotal' => ($produk->harga * ($item['quantity'] ?? 1)) -
                        (($produk->harga * ($item['quantity'] ?? 1) * ($produk->diskon ?? 0)) / 100)
                ];
            });

            // Calculate totals
            $totalPrice = $directItems->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            $totalDiscount = $directItems->sum(function ($item) {
                return ($item['price'] * $item['quantity'] * $item['discount']) / 100;
            });

            $grandTotal = $totalPrice - $totalDiscount;

            return view('home.checkout', [
                'cartItems' => $directItems,
                'totalPrice' => $totalPrice,
                'totalDiscount' => $totalDiscount,
                'grandTotal' => $grandTotal,
                'isFromCart' => false,
                'checkoutItems' => $items,
                'addresses' => $addresses
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    // Memproses checkout
    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:Cash on Delivery,Transfer',
            'address_id' => 'required|exists:addresses,id',
            'seller_notes' => 'nullable|string',
            'proof_of_payment' => 'required_if:payment_method,Transfer|file|image|max:2048',
        ]);

        $user = auth()->user();
        $address = Address::find($request->address_id);

        try {
            return DB::transaction(function () use ($request, $address) {
                $validated = $request->validate([
                    'payment_method' => 'required|in:Cash on Delivery,Transfer',
                    'items' => 'required|json',
                    'proof_of_payment' => 'required_if:payment_method,Transfer|file|image|max:2048',
                    'seller_notes' => 'nullable|string|max:1000' // Add validation for notes
                ]);

                $items = json_decode($validated['items'], true);

                if (empty($items)) {
                    throw new \Exception('Tidak ada item yang dipilih');
                }

                // Verifikasi keberadaan produk dan stok
                foreach ($items as $item) {
                    $produk = Produk::find($item['id']);
                    if (!$produk) {
                        throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan");
                    }

                    // Validasi stok
                    if ($produk->stok < $item['quantity']) {
                        throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi");
                    }
                }

                // Hitung total
                $totalAmount = 0;
                foreach ($items as $item) {
                    $totalAmount += ($item['price'] * $item['quantity']) -
                        (($item['price'] * $item['quantity'] * $item['discount']) / 100);
                }

                // Buat order
                $order = Orders::create([
                    'user_id' => Auth::id(),
                    // 'produk_id' => $items[0]['id'],
                    'payment_proof' => $validated['payment_proof'] ?? null,
                    'payment_method' => $validated['payment_method'],
                    'status' => $validated['payment_method'] === 'Cash on Delivery' ? 'pending' : 'awaiting_payment',
                    'total_amount' => $totalAmount,
                    'qty' => array_sum(array_column($items, 'quantity')),
                    'order_date' => now(),
                    'notes' => $validated['seller_notes'] ?? null, // Add notes to order
                    'address_id' => $address->id
                ]);

                // Handle bukti pembayaran
                if ($validated['payment_method'] === 'Transfer' && $request->hasFile('proof_of_payment')) {
                    $path = $request->file('proof_of_payment')
                        ->store('proof_of_payments/' . Auth::id(), 'public');
                    $order->update(['payment_proof' => $path]);
                }

                // Buat order items
                foreach ($items as $item) {
                    OrderItems::create([
                        'order_id' => $order->id,
                        'produk_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'discount' => $item['discount'],
                        'subtotal' => ($item['price'] * $item['quantity']) -
                            (($item['price'] * $item['quantity'] * $item['discount']) / 100)
                    ]);

                    // Kurangi stok
                    $produk = Produk::find($item['id']);
                    $produk->decrement('stok', $item['quantity']);
                }

                // Jika checkout dari cart, hapus item cart
                if ($request->input('isFromCart', true)) {
                    Cart::where('user_id', Auth::id())
                        ->whereIn('produk_id', array_column($items, 'id'))
                        ->delete();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat',
                    'order_id' => $order->id,
                    'redirect_url' => route('orders.confirmation', ['order' => $order->id])
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmation($orderId)
    {
        $order = Orders::with(['items', 'items.product', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        return view('home.confirmation', compact('order'));
    }
}