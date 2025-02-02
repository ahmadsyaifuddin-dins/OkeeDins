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

class CheckoutController extends Controller
{
    private $transactionController;

    public function __construct()
    {
        $this->middleware('auth');
        $this->transactionController = new TransactionController();
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // Handle direct buy
        if ($request->has('direct_buy') && $request->has('produk_id') && $request->has('quantity')) {
            $product = Produk::findOrFail($request->produk_id);
            $quantity = (int) $request->quantity;

            if ($quantity < 1) {
                return redirect()->back()->with('error', 'Jumlah produk tidak valid');
            }

            // Hitung total
            $itemTotal = $product->harga * $quantity;
            $itemDiscount = ($itemTotal * $product->diskon) / 100;
            $finalPrice = $itemTotal - $itemDiscount;

            // Buat array untuk ditampilkan di view
            $directBuyItem = (object) [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $itemTotal,
                'discount' => $itemDiscount,
                'final_price' => $finalPrice
            ];

            return view('home.checkout', [
                'directBuyItem' => $directBuyItem,
                'cartItems' => collect(), // Kirim empty collection untuk cartItems
                'addresses' => $user->addresses,
                'subTotal' => $itemTotal,
                'totalDiscount' => $itemDiscount,
                'grandTotal' => $finalPrice,
                'isDirect' => true
            ]);
        }

        // Handle cart checkout
        if (!$request->has('items')) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih minimal satu produk untuk checkout');
        }

        $items = $request->input('items');

        // Jika items adalah string dengan koma, pecah menjadi array
        if (is_string($items)) {
            $items = explode(',', $items);
        }
        // Jika items sudah array, gunakan langsung
        else if (!is_array($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Format data tidak valid');
        }

        // Get cart items that were selected
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->whereIn('id', $items)
            ->get();

        // Log untuk debugging
        Log::info('Selected items:', ['items' => $items]);
        Log::info('Cart items:', ['cartItems' => $cartItems]);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Produk yang dipilih tidak ditemukan');
        }

        // Calculate totals
        $subTotal = 0;
        $totalDiscount = 0;
        $totalQty = 0;

        foreach ($cartItems as $item) {
            $itemTotal = $item->product->harga * $item->quantity;
            $itemDiscount = ($itemTotal * $item->product->diskon) / 100;

            $subTotal += $itemTotal;
            $totalDiscount += $itemDiscount;
            $totalQty += $item->quantity;
        }

        $grandTotal = $subTotal - $totalDiscount;

        return view('home.checkout', compact('cartItems', 'subTotal', 'totalDiscount', 'grandTotal', 'totalQty'));
    }

    public function showCheckout(Request $request)
    {
        $user = auth()->user();
        $itemIds = $request->query('itemIds');

        // Handle direct buy
        if ($request->has('product_id')) {
            $product = Produk::findOrFail($request->product_id);
            $quantity = $request->quantity ?? 1;

            return view('home.checkout', [
                'cartItems' => collect([
                    (object)[
                        'product' => $product,
                        'quantity' => $quantity
                    ]
                ]),
                'addresses' => $user->addresses,
                'directBuy' => true
            ]);
        }

        // Handle cart checkout
        if (!$itemIds) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada produk yang dipilih untuk checkout');
        }

        // Convert comma-separated string to array
        $itemIds = explode(',', $itemIds);

        // Ambil data cart items berdasarkan ID yang dipilih
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->whereIn('id', $itemIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Produk yang dipilih tidak ditemukan');
        }

        // Hitung total
        $subTotal = 0;
        $totalDiscount = 0;
        $totalQty = 0;

        foreach ($cartItems as $item) {
            $itemTotal = $item->product->harga * $item->quantity;
            $itemDiscount = ($itemTotal * $item->product->diskon) / 100;

            $subTotal += $itemTotal;
            $totalDiscount += $itemDiscount;
        }

        $grandTotal = $subTotal - $totalDiscount;

        return view('home.checkout', [
            'cartItems' => $cartItems,
            'addresses' => $user->addresses,
            'subTotal' => $subTotal,
            'totalDiscount' => $totalDiscount,
            'grandTotal' => $grandTotal,
            'totalQty' => $totalQty
        ]);
    }

    public function process(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'address_id' => 'required|exists:addresses,id',
                'payment_method' => 'required|in:transfer,Cash on Delivery',
                'notes' => 'nullable|string|max:500',
                'voucher_code' => 'nullable|string|exists:vouchers,code',
                'direct_buy' => 'nullable|boolean',
                'produk_id' => 'required_if:direct_buy,true|exists:produk,id',
                'quantity' => 'required_if:direct_buy,true|integer|min:1'
            ]);

            DB::beginTransaction();

            $user = auth()->user();
            $orderNumber = 'ORD-' . uniqid();

            // Create order
            $order = Orders::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'address_id' => $validated['address_id'],
                'total_amount' => $request->grand_total,
                'qty' => $request->total_qty,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending'
            ]);

            $subTotal = 0;
            $totalDiscount = 0;
            $totalQty = 0;

            if ($request->direct_buy) {
                // Process direct buy
                $product = Produk::findOrFail($request->produk_id);
                $quantity = $request->quantity;

                $itemTotal = $product->harga * $quantity;
                $itemDiscount = ($itemTotal * $product->diskon) / 100;
                $finalPrice = $itemTotal - $itemDiscount;

                // Create order item
                OrderItems::create([
                    'order_id' => $order->id,
                    'produk_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->harga,
                    'discount' => $product->diskon,
                    'subtotal' => $finalPrice
                ]);

                $subTotal = $itemTotal;
                $totalDiscount = $itemDiscount;
                $totalQty = $quantity;
            } else {
                // Process cart items
                $cartItems = Cart::with('product')
                    ->where('user_id', $user->id)
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('Tidak ada produk di keranjang');
                }

                foreach ($cartItems as $item) {
                    $product = $item->product;
                    $itemTotal = $product->harga * $item->quantity;
                    $itemDiscount = ($itemTotal * $product->diskon) / 100;
                    $finalPrice = $itemTotal - $itemDiscount;

                    // Create order item
                    OrderItems::create([
                        'order_id' => $order->id,
                        'produk_id' => $product->id,
                        'quantity' => $item->quantity,
                        'price' => $product->harga,
                        'discount' => $product->diskon,
                        'subtotal' => $finalPrice
                    ]);

                    $subTotal += $itemTotal;
                    $totalDiscount += $itemDiscount;
                    $totalQty += $item->quantity;

                    // Remove from cart
                    $item->delete();
                }
            }

            // Apply voucher if exists
            $voucherDiscount = 0;
            if (!empty($validated['voucher_code'])) {
                $voucher = Voucher::where('code', $validated['voucher_code'])->first();
                if ($voucher) {
                    $voucherDiscount = $this->applyVoucher($voucher, $subTotal);
                }
            }

            // Update order totals
            $order->update([
                'subtotal' => $subTotal,
                'discount' => $totalDiscount,
                'voucher_discount' => $voucherDiscount,
                'total_amount' => $subTotal - $totalDiscount - $voucherDiscount,
                'qty' => $totalQty
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'redirect' => route('orders.detail', $order->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function procesDirectBuyCheckout($user, $data, $request)
    // {
    //     $produkId = (int) str_replace('direct_', '', $data['selected_items'][0]);
    //     $product = Produk::findOrFail($produkId);
    //     $quantity = $request->input('quantity', 1);

    //     // Validate stock
    //     if ($quantity > $product->stok) {
    //         throw new \Exception('Stok produk tidak mencukupi');
    //     }

    //     // Calculate price and discount
    //     $price = $product->harga * $quantity;
    //     $discount = ($price * $product->diskon) / 100;
    //     $totalAmount = $price - $discount;

    //     // Handle voucher
    //     $voucherResult = $this->applyVoucher($data['voucher_code'], $totalAmount);

    //     // Create order
    //     $order = Orders::create([
    //         'user_id' => $user->id,
    //         'address_id' => $data['selected_address'],
    //         'payment_method' => $data['payment_method'],
    //         'notes' => $data['notes'] ?? '',
    //         'status' => 'pending',
    //         'total_amount' => $voucherResult['total_amount'],
    //         'qty' => $quantity,
    //         'order_number' => 'ORD-' . uniqid(),
    //         'payment_status' => 'unpaid',
    //         'voucher_id' => $voucherResult['voucher_id'],
    //         'voucher_discount' => $voucherResult['voucher_discount']
    //     ]);

    //     try {
    //         // Create transaction record
    //         $transaction = $this->transactionController->createTransaction($order);
    //         Log::info('Transaction created:', $transaction->toArray());
    //     } catch (\Exception $e) {
    //         Log::error('Error creating transaction: ' . $e->getMessage());
    //         throw $e;
    //     }

    //     // Create order item
    //     OrderItems::create([
    //         'order_id' => $order->id,
    //         'produk_id' => $product->id,
    //         'quantity' => $quantity,
    //         'price' => $product->harga,
    //         'discount' => $product->diskon,
    //         'subtotal' => $price - $discount
    //     ]);

    //     // Update stock
    //     $product->decrement('stok', $quantity);

    //     // Record voucher usage if applicable
    //     if ($voucherResult['voucher']) {
    //         $this->recordVoucherUsage($user, $voucherResult['voucher'], $order, $voucherResult['voucher_discount']);
    //     }

    //     return ['order' => $order];
    // }

    // public function processCartCheckout($user, $data)
    // {
    //     $cartItems = Cart::with('product')
    //         ->where('user_id', $user->id)
    //         ->whereIn('id', $data['selected_items'])
    //         ->get();

    //     if ($cartItems->isEmpty()) {
    //         throw new \Exception('Tidak ada produk yang dipilih');
    //     }

    //     // Validate stock
    //     foreach ($cartItems as $item) {
    //         if ($item->quantity > $item->product->stok) {
    //             throw new \Exception("Stok {$item->product->nama_produk} tidak mencukupi");
    //         }
    //     }

    //     // Calculate total amount
    //     $totalQty = 0;
    //     $totalAmount = 0;
    //     foreach ($cartItems as $item) {
    //         $totalQty += $item->quantity;
    //         $price = $item->product->harga * $item->quantity;
    //         $discount = ($price * $item->product->diskon) / 100;
    //         $totalAmount += $price - $discount;
    //     }

    //     // Handle voucher
    //     $voucherResult = $this->applyVoucher($data['voucher_code'], $totalAmount);

    //     // Create order
    //     $order = Orders::create([
    //         'user_id' => $user->id,
    //         'address_id' => $data['selected_address'],
    //         'payment_method' => $data['payment_method'],
    //         'notes' => $data['notes'] ?? '',
    //         'status' => 'pending',
    //         'total_amount' => $voucherResult['total_amount'],
    //         'qty' => $totalQty,
    //         'order_number' => 'ORD-' . uniqid(),
    //         'payment_status' => 'unpaid',
    //         'voucher_id' => $voucherResult['voucher_id'],
    //         'voucher_discount' => $voucherResult['voucher_discount']
    //     ]);

    //     try {
    //         // Create transaction record
    //         $transaction = $this->transactionController->createTransaction($order);
    //         Log::info('Transaction created:', $transaction->toArray());
    //     } catch (\Exception $e) {
    //         Log::error('Error creating transaction: ' . $e->getMessage());
    //         throw $e;
    //     }

    //     // Create order items and update stock
    //     foreach ($cartItems as $item) {
    //         OrderItems::create([
    //             'order_id' => $order->id,
    //             'produk_id' => $item->product->id,
    //             'quantity' => $item->quantity,
    //             'price' => $item->product->harga,
    //             'discount' => $item->product->diskon,
    //             'subtotal' => ($item->product->harga * $item->quantity) -
    //                 (($item->product->harga * $item->quantity * $item->product->diskon) / 100)
    //         ]);

    //         // Update stock
    //         $item->product->decrement('stok', $item->quantity);
    //     }

    //     // Clear cart items
    //     Cart::whereIn('id', $data['selected_items'])->delete();

    //     // Record voucher usage if applicable
    //     if ($voucherResult['voucher']) {
    //         $this->recordVoucherUsage($user, $voucherResult['voucher'], $order, $voucherResult['voucher_discount']);
    //     }

    //     return ['order' => $order];
    // }

    public function processCheckout(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $data = $request->validate([
                'selected_address' => 'required|exists:addresses,id',
                'payment_method' => 'required|in:transfer,Cash on Delivery',
                'notes' => 'nullable|string',
                'voucher_code' => 'nullable|string|exists:vouchers,code',
                'total_amount' => 'required|numeric|min:0',
                'selected_items' => 'required|array',
                'selected_items.*' => 'required|string',
                'quantity' => 'required_if:direct_buy,1|integer|min:1'
            ]);

            // Determine checkout type
            $isDirectBuy = isset($data['selected_items'][0]) && strpos($data['selected_items'][0], 'direct_') === 0;

            if ($isDirectBuy) {
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
            'order_number' => 'ORD-' . uniqid(),
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

        // Calculate totals
        $subTotal = 0;
        $totalDiscount = 0;
        $totalQty = 0;

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

        $finalAmount = $subTotal - $totalDiscount;

        // Create order
        $order = Orders::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . uniqid(),
            'address_id' => $data['selected_address'],
            'payment_method' => $data['payment_method'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'subtotal' => $subTotal,
            'discount' => $totalDiscount,
            'total_amount' => $finalAmount,
            'qty' => $totalQty
        ]);

        // Create order items and update stock
        foreach ($cartItems as $item) {
            $itemTotal = $item->product->harga * $item->quantity;
            $itemDiscount = ($itemTotal * $item->product->diskon) / 100;
            $finalPrice = $itemTotal - $itemDiscount;

            OrderItems::create([
                'order_id' => $order->id,
                'produk_id' => $item->product->id,
                'quantity' => $item->quantity,
                'price' => $item->product->harga,
                'discount' => $item->product->diskon,
                'subtotal' => $finalPrice
            ]);

            // Update stock
            $item->product->decrement('stok', $item->quantity);
        }

        // Clear cart items
        Cart::whereIn('id', $data['selected_items'])->delete();

        return ['order' => $order];
    }



    private function applyVoucher($voucher, $totalAmount)
    {
        if ($voucher->min_purchase && $totalAmount < $voucher->min_purchase) {
            throw new \Exception('Total pembelian belum memenuhi syarat minimum voucher');
        }

        if ($voucher->type === 'percentage') {
            return ($totalAmount * $voucher->value) / 100;
        }

        return $voucher->value;
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
                'qty' => $request->qty
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'produk_id' => $item->product_id,
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