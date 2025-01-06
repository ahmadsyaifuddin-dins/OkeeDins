<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan halaman checkout
    public function showCheckout()
    {
        // Mengambil cart items yang terseleksi dari database
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereHas('product') // Memastikan produk masih ada
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada produk dalam keranjang');
        }

        return view('home.checkout', compact('cartItems'));
    }

    // Memproses checkout
    public function processCheckout(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validasi request
                $validated = $request->validate([
                    'payment_method' => 'required|in:cod,transfer',
                    'items' => 'required|json',
                    'proof_of_payment' => 'required_if:payment_method,transfer|file|image|max:2048'
                ]);

                // Decode items JSON
                $items = json_decode($validated['items'], true);

                if (empty($items)) {
                    throw new \Exception('Tidak ada item yang dipilih');
                }

                // Verifikasi keberadaan produk sebelum membuat order
                foreach ($items as $item) {
                    $produk = Produk::find($item['id']);
                    if (!$produk) {
                        throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan");
                    }
                }

                // Hitung total amount
                $totalAmount = 0;
                foreach ($items as $item) {
                    $totalAmount += ($item['price'] * $item['quantity']) -
                        (($item['price'] * $item['quantity'] * $item['discount']) / 100);
                }

                // Buat order baru
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'payment_method' => $validated['payment_method'],
                    'status' => $validated['payment_method'] === 'cod' ? 'pending' : 'awaiting_payment',
                    'total_amount' => $totalAmount,
                    'qty' => array_sum(array_column($items, 'quantity')),
                    'order_date' => now(),
                ]);

                // Handle bukti pembayaran untuk metode transfer
                if ($validated['payment_method'] === 'transfer' && $request->hasFile('proof_of_payment')) {
                    $path = $request->file('proof_of_payment')
                        ->store('proof_of_payments/' . Auth::id(), 'public');
                    $order->update(['payment_proof' => $path]);
                }

                // Buat order items dengan nama kolom yang benar
                foreach ($items as $item) {
                    OrderItems::create([
                        'order_id' => $order->id,
                        'produk_id' => $item['id'], // Ubah dari product_id menjadi produk_id
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'discount' => $item['discount'],
                        'subtotal' => ($item['price'] * $item['quantity']) -
                            (($item['price'] * $item['quantity'] * $item['discount']) / 100)
                    ]);
                }

                // Hapus item dari cart
                Cart::where('user_id', Auth::id())
                    ->whereIn('produk_id', array_column($items, 'id')) // Ubah dari product_id menjadi produk_id
                    ->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat',
                    'order_id' => $order->id,
                    'redirect_url' => route('checkout.process', ['order' => $order->id])
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Checkout Error: ' . $e->getMessage()); // Tambah logging
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Halaman konfirmasi order
    public function confirmation($orderId)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        return view('home.confirmation', compact('order'));
    }
}
