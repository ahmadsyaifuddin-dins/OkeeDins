<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Produk;
use App\Models\Voucher;
use App\Models\VoucherUser;
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
        $itemIds = $request->query('items');
        $directBuy = $request->query('direct_buy');
        $produkId = $request->query('produk_id');
        $quantity = $request->query('quantity', 1);

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

        // Handle direct buy from product detail
        if ($directBuy && $produkId) {
            $product = Produk::findOrFail($produkId);
            
            // Validate quantity
            if ($quantity < 1 || $quantity > $product->stok) {
                return redirect()->route('produk.detail', $product->slug)
                    ->with('error', 'Jumlah produk tidak valid');
            }

            // Create virtual cart item
            $cartItems = collect([
                (object)[
                    'id' => 'direct_' . $product->id,
                    'product' => $product,
                    'quantity' => $quantity,
                    'produk_id' => $product->id
                ]
            ]);

            $price = $product->harga * $quantity;
            $discount = ($price * $product->diskon) / 100;
            
            return view('home.checkout', [
                'cartItems' => $cartItems,
                'totalPrice' => $price,
                'totalDiscount' => $discount,
                'grandTotal' => $price - $discount,
                'cartTotal' => $price - $discount,
                'addresses' => $addresses,
                'directBuy' => true
            ]);
        }

        // Handle cart checkout
        if (!$itemIds) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada produk yang dipilih untuk checkout');
        }

        $itemIds = explode(',', $itemIds);
        if (empty($itemIds)) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada produk yang dipilih untuk checkout');
        }

        // Ambil data cart items berdasarkan ID yang dipilih
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $itemIds)
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
            'cartTotal' => $grandTotal,
            'addresses' => $addresses,
            'directBuy' => false
        ]);
    }

    public function processCheckout(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $data = $request->validate([
                'address_id' => 'required|exists:addresses,id',
                'payment_method' => 'required|in:transfer,Cash on Delivery',
                'notes' => 'nullable|string',
                'voucher_code' => 'nullable|exists:vouchers,code',
                'total_amount' => 'required|numeric|min:0',
                'selected_items' => 'required|array',
                'selected_items.*' => 'required|string',
                'quantity' => 'required_if:direct_buy,1|integer|min:1'
            ]);

            // Handle direct buy
            if (isset($data['selected_items'][0]) && strpos($data['selected_items'][0], 'direct_') === 0) {
                $produkId = (int) str_replace('direct_', '', $data['selected_items'][0]);
                $product = Produk::findOrFail($produkId);
                $quantity = $request->input('quantity', 1);

                // Validate quantity
                if ($quantity > $product->stok) {
                    throw new \Exception('Stok produk tidak mencukupi');
                }

                // Buat order baru
                $order = Orders::create([
                    'user_id' => $user->id,
                    'address_id' => $data['address_id'],
                    'payment_method' => $data['payment_method'],
                    'notes' => $data['notes'] ?? '',
                    'status' => 'pending',
                    'total_amount' => $data['total_amount'],
                    'qty' => $quantity,
                    'order_number' => 'ORD-' . uniqid(),
                    'payment_status' => 'unpaid'
                ]);

                // Create order item
                OrderItems::create([
                    'order_id' => $order->id,
                    'produk_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->harga,
                    'discount' => $product->diskon,
                    'subtotal' => ($product->harga * $quantity) -
                        (($product->harga * $quantity * $product->diskon) / 100)
                ]);

                // Update stok produk
                $product->decrement('stok', $quantity);

            } else {
                // Handle cart checkout
                $cartItems = Cart::with('product')
                    ->where('user_id', $user->id)
                    ->whereIn('id', $data['selected_items'])
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('Tidak ada produk yang dipilih');
                }

                // Validate stock for all items
                foreach ($cartItems as $item) {
                    if ($item->quantity > $item->product->stok) {
                        throw new \Exception("Stok {$item->product->nama_produk} tidak mencukupi");
                    }
                }

                // Buat order baru
                $order = Orders::create([
                    'user_id' => $user->id,
                    'address_id' => $data['address_id'],
                    'payment_method' => $data['payment_method'],
                    'notes' => $data['notes'] ?? '',
                    'status' => 'pending',
                    'total_amount' => $data['total_amount'],
                    'qty' => $cartItems->sum('quantity'),
                    'order_number' => 'ORD-' . uniqid(),
                    'payment_status' => 'unpaid'
                ]);

                // Create order items
                foreach ($cartItems as $item) {
                    OrderItems::create([
                        'order_id' => $order->id,
                        'produk_id' => $item->produk_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->harga,
                        'discount' => $item->product->diskon,
                        'subtotal' => ($item->product->harga * $item->quantity) -
                            (($item->product->harga * $item->quantity * $item->product->diskon) / 100)
                    ]);

                    // Update stok produk
                    $item->product->decrement('stok', $item->quantity);

                    // Delete from cart
                    $item->delete();
                }
            }

            // Apply voucher if used
            if (!empty($data['voucher_code'])) {
                $voucher = Voucher::where('code', $data['voucher_code'])->first();
                if ($voucher) {
                    // Create voucher usage record
                    VoucherUser::create([
                        'user_id' => $user->id,
                        'voucher_id' => $voucher->id,
                        'discount_amount' => $voucher->value,
                        'order_id' => $order->id,
                        'used_at' => now()
                    ]);

                    // Increment used_count
                    $voucher->increment('used_count');
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat!',
                'order_id' => $order->id,
                'payment_method' => $data['payment_method']
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses checkout.',
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Oops...',
                    'text' => 'Terjadi kesalahan saat memproses checkout.',
                    'footer' => '<a href="#">Hubungi dukungan</a>',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'OK',
                    'showCancelButton' => false,
                ]
            ], 422);
        }
    }

    public function validateVoucher(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu'
                ], 401);
            }

            $request->validate([
                'code' => 'required|string',
                'subtotal' => 'required|numeric|min:0'
            ]);

            $voucher = Voucher::where('code', $request->code)
                ->where('is_active', true)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->first();

            if (!$voucher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher tidak ditemukan atau sudah tidak berlaku'
                ]);
            }

            // Check if voucher has reached max usage
            if ($voucher->max_uses > 0) {
                $usageCount = VoucherUser::where('voucher_id', $voucher->id)->count();
                if ($usageCount >= $voucher->max_uses) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Voucher sudah mencapai batas penggunaan'
                    ]);
                }
            }

            // Check if user has used this voucher before
            $hasUsed = VoucherUser::where('voucher_id', $voucher->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($hasUsed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah menggunakan voucher ini'
                ]);
            }

            // Check minimum purchase requirement
            if ($voucher->min_purchase > 0 && $request->subtotal < $voucher->min_purchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total belanja minimum Rp ' . number_format($voucher->min_purchase, 0, ',', '.') . ' untuk menggunakan voucher ini'
                ]);
            }

            // Calculate discount
            $discount = $voucher->type === 'fixed' 
                ? $voucher->value
                : ($request->subtotal * $voucher->value / 100);

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil diterapkan',
                'voucher' => $voucher,
                'discount' => $discount
            ]);

        } catch (\Exception $e) {
            Log::error('Voucher validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memvalidasi voucher'
            ], 500);
        }
    }

    public function processOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get cart items
            $cartItems = Cart::where('user_id', auth()->id())->get();
            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Keranjang belanja kosong.');
            }

            // Validate voucher if applied
            $voucherId = $request->voucher_id;
            $discountAmount = 0;
            
            if ($voucherId) {
                $voucher = Voucher::find($voucherId);
                if ($voucher) {
                    // Create voucher usage record
                    VoucherUser::create([
                        'voucher_id' => $voucher->id,
                        'user_id' => auth()->id(),
                        'used_at' => now()
                    ]);
                    
                    $discountAmount = $request->discount_amount;
                }
            }

            // Create order
            $order = Orders::create([
                'user_id' => auth()->id(),
                'total_amount' => $request->total_amount,
                'shipping_address' => $request->shipping_address,
                'shipping_cost' => $request->shipping_cost,
                'voucher_id' => $voucherId,
                'discount_amount' => $discountAmount,
                'status' => 'pending'
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->quantity * $item->product->price
                ]);
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();
            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order processing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function showPayment($orderId)
    {
        $order = Orders::with(['orderItems.produk', 'address'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        return view('home.payment', compact('order'));
    }

    public function confirmPayment(Request $request, $orderId)
    {
        try {
            $request->validate([
                'payment_proof' => 'required|image|max:2048'
            ]);

            $order = Orders::where('user_id', Auth::id())->findOrFail($orderId);
            
            // Upload bukti pembayaran
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            // Update order
            $order->update([
                'payment_proof' => $path,
                'payment_status' => 'pending',
                'status' => 'processing'
            ]);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Mohon tunggu konfirmasi dari admin.');

        } catch (\Exception $e) {
            Log::error('Payment confirmation error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
