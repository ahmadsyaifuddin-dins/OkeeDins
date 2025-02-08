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
use App\Http\Controllers\TransactionController;

class CheckoutControllerOld extends Controller
{
    private $transactionController;

    public function __construct()
    {
        $this->middleware('auth');
        $this->transactionController = new TransactionController();
    }

    public function showCheckout(Request $request)
    {
        $user = auth()->user();
        $itemIds = $request->query('items'); // Array of cart item IDs
        $directBuy = $request->query('direct_buy'); // Flag for direct buy
        $produkId = $request->query('produk_id');
        $quantity = $request->query('quantity', 1); // Default quantity is 1

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

            $itemTotal = $product->harga * $quantity;
            $price = $product->harga * $quantity;
            $discount = ($price * $product->diskon) / 100;

            return view('home.checkout', [
                'cartItems' => $cartItems,
                'totalPrice' => $price,
                'totalDiscount' => $discount,
                'subtotal' => $itemTotal,
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
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $data = $request->validate([
                'selected_address' => 'required|exists:addresses,id',
                'payment_method' => 'required|in:transfer,Cash on Delivery',
                'notes' => 'nullable|string',
                'voucher_code' => 'nullable|exists:vouchers,code',
                'total_amount' => 'required|numeric|min:0',
                'selected_items' => 'required|array',
                'selected_items.*' => 'required|string',
                'quantity' => 'required_if:direct_buy,1|integer|min:1'
            ]);

            // Determine checkout type and get quantity
            $isDirectBuy = isset($data['selected_items'][0]) && strpos($data['selected_items'][0], 'direct_') === 0;
            
            if ($isDirectBuy) {
                $quantity = $request->input('quantity');
                if (!$quantity) {
                    throw new \Exception('Quantity is required for direct buy');
                }
                $data['quantity'] = $quantity;
                $checkoutData = $this->procesDirectBuyCheckout($user, $data, $request);
            } else {
                $checkoutData = $this->processCartCheckout($user, $data);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat!',
                'order_id' => $checkoutData['order']->id,
                'payment_method' => $data['payment_method']
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->handleCheckoutError($e);
        }
    }

    private function procesDirectBuyCheckout($user, $data, $request)
    {
        $produkId = (int) str_replace('direct_', '', $data['selected_items'][0]);
        $product = Produk::findOrFail($produkId);
        $quantity = $request->input('quantity', 1);

        // Validate stock
        if ($quantity > $product->stok) {
            throw new \Exception('Stok produk tidak mencukupi');
        }

        // Calculate price and discount
        $price = $product->harga * $quantity;
        $discount = ($price * $product->diskon) / 100;
        $totalAmount = $price - $discount;

        // Handle voucher
        $voucherResult = $this->applyVoucher($data['voucher_code'], $totalAmount);

        // Create order
        $order = Orders::create([
            'user_id' => $user->id,
            'address_id' => $data['selected_address'],
            'payment_method' => $data['payment_method'],
            'notes' => $data['notes'] ?? '',
            'status' => 'pending',
            'total_amount' => $voucherResult['total_amount'],
            'qty' => $quantity,
            'order_number' => sprintf('ORD-%06d', rand(1, 999999)),
            'payment_status' => 'unpaid',
            'voucher_id' => $voucherResult['voucher_id'],
            'voucher_discount' => $voucherResult['voucher_discount']
        ]);

        try {
            // Create transaction record
            $transaction = $this->transactionController->createTransaction($order);
            Log::info('Transaction created:', $transaction->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating transaction: ' . $e->getMessage());
            throw $e;
        }

        // Create order item
        OrderItems::create([
            'order_id' => $order->id,
            'produk_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->harga,
            'discount' => $product->diskon,
            'subtotal' => $price - $discount
        ]);

        // Update stock
        $product->decrement('stok', $quantity);

        // Record voucher usage if applicable
        if ($voucherResult['voucher']) {
            $this->recordVoucherUsage($user, $voucherResult['voucher'], $order, $voucherResult['voucher_discount']);
        }

        return ['order' => $order];
    }

    private function processCartCheckout($user, $data)
    {
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->whereIn('id', $data['selected_items'])
            ->get();

        if ($cartItems->isEmpty()) {
            throw new \Exception('Tidak ada produk yang dipilih');
        }

        // Validate stock
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stok) {
                throw new \Exception("Stok {$item->product->nama_produk} tidak mencukupi");
            }
        }

        // Calculate total amount
        $totalQty = 0;
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalQty += $item->quantity;
            $price = $item->product->harga * $item->quantity;
            $discount = ($price * $item->product->diskon) / 100;
            $totalAmount += $price - $discount;
        }

        // Handle voucher
        $voucherResult = $this->applyVoucher($data['voucher_code'], $totalAmount);

        // Create order
        $order = Orders::create([
            'user_id' => $user->id,
            'address_id' => $data['selected_address'],
            'payment_method' => $data['payment_method'],
            'notes' => $data['notes'] ?? '',
            'status' => 'pending',
            'total_amount' => $voucherResult['total_amount'],
            'qty' => $totalQty,
            'order_number' => sprintf('ORD-%06d', rand(1, 999999)),
            'payment_status' => 'unpaid',
            'voucher_id' => $voucherResult['voucher_id'],
            'voucher_discount' => $voucherResult['voucher_discount']
        ]);

        try {
            // Create transaction record
            $transaction = $this->transactionController->createTransaction($order);
            Log::info('Transaction created:', $transaction->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating transaction: ' . $e->getMessage());
            throw $e;
        }

        // Create order items and update stock
        foreach ($cartItems as $item) {
            OrderItems::create([
                'order_id' => $order->id,
                'produk_id' => $item->product->id,
                'quantity' => $item->quantity,
                'price' => $item->product->harga,
                'discount' => $item->product->diskon,
                'subtotal' => ($item->product->harga * $item->quantity) -
                    (($item->product->harga * $item->quantity * $item->product->diskon) / 100)
            ]);

            // Update stock
            $item->product->decrement('stok', $item->quantity);
        }

        // Clear cart items
        Cart::whereIn('id', $data['selected_items'])->delete();

        // Record voucher usage if applicable
        if ($voucherResult['voucher']) {
            $this->recordVoucherUsage($user, $voucherResult['voucher'], $order, $voucherResult['voucher_discount']);
        }

        return ['order' => $order];
    }

    private function applyVoucher($voucherCode, $totalAmount)
    {
        $voucherDiscount = 0;
        $voucherId = null;
        $voucher = null;

        if (!empty($voucherCode)) {
            $voucher = Voucher::where('code', $voucherCode)
                ->where('is_active', true)
                ->first();

            if ($voucher) {
                $voucherDiscount = $voucher->type === 'fixed'
                    ? min($voucher->value, $totalAmount)
                    : ($totalAmount * $voucher->value / 100);

                $totalAmount -= $voucherDiscount;
                $voucherId = $voucher->id;
            }
        }

        return [
            'total_amount' => $totalAmount,
            'voucher_discount' => $voucherDiscount,
            'voucher_id' => $voucherId,
            'voucher' => $voucher
        ];
    }

    private function recordVoucherUsage($user, $voucher, $order, $discountAmount)
    {
        VoucherUser::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
            'discount_amount' => $discountAmount,
            'order_id' => $order->id,
            'used_at' => now()
        ]);

        $voucher->increment('used_count');
    }

    private function handleCheckoutError($e)
    {
        Log::error('Checkout error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memproses checkout.',
            'error' => $e->getMessage(),
            'alert' => [
                'icon' => 'error',
                'title' => 'Oops...',
                'text' => 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage(),
                'footer' => '<a href="https://telegram.me/dins_ahmads">Hubungi dukungan</a>',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'showCancelButton' => false,
            ]
        ], 422);
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
                'code' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value !== strtoupper($value)) {
                            $fail('Kode voucher harus dalam huruf kapital.');
                        }
                    }
                ],
                'subtotal' => 'required|numeric|min:0'
            ]);

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
            $voucherDiscount = $request->discount_amount ?? 0; // Ambil dari input form atau set 0 jika tidak ada

            if ($voucherId) {
                $voucher = Voucher::find($voucherId);
                if (!$voucher) {
                    return redirect()->back()->with('error', 'Voucher tidak valid');
                }
                $discountAmount = $voucherDiscount;
            }

            // Create order
            $order = Orders::create([
                'user_id' => Auth::id(),
                'total_amount' => $request->total_amount,
                'shipping_cost' => $request->shipping_cost,
                'voucher_id' => $voucherId,
                'discount_amount' => $discountAmount,
                'status' => 'pending',
                'voucher_discount' => $voucherDiscount // Pastikan field ini selalu ada
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
            return redirect()->route('orders.detail', $order->id)->with('success', 'Pesanan berhasil dibuat!');
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
        $order = Orders::findOrFail($orderId);

        if ($order->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Pembayaran sudah dikonfirmasi sebelumnya');
        }

        DB::beginTransaction();
        try {
            // Update order status
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing'
            ]);

            // Update transaction status
            $this->transactionController->updateTransactionStatus($order->id, 'paid', $order->payment_method);

            DB::commit();
            return redirect()->route('orders.show', $order->id)->with('success', 'Pembayaran berhasil dikonfirmasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengkonfirmasi pembayaran');
        }
    }
}