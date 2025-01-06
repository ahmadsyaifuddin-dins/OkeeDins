<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getCartCount()
    {
        return $this->getCartQuery()->count();
    }

    public function add(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id', // Memastikan produk_id ada di tabel produk dengan kolom id
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        try {
            \Log::info('Request Data:', $request->all());

            $existingCart = $this->getCartQuery()
                ->where('produk_id', $request->produk_id)
                ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $request->quantity;
                $this->updateExistingCart($existingCart, $newQuantity);

                return response()->json([
                    'success' => true,
                    'message' => 'Quantity produk berhasil diperbarui',
                    'cartCount' => $this->getCartCount()
                ]);
            }

            $this->createNewCart($request);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cartCount' => $this->getCartCount()
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart Error: ' . $e->getMessage());
            \Log::error('Error Stack Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk ke keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->where('status', 'new')
            ->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $totalDiscount = $cartItems->sum(function ($item) {
            return ($item->product->discount ?? 0) * $item->quantity;
        });

        $grandTotal = $totalPrice - $totalDiscount;

        return view('home.cart', compact('cartItems', 'totalPrice', 'totalDiscount', 'grandTotal'));
    }


    private function getCartQuery()
    {
        return Cart::where('user_id', auth()->id())->where('status', 'new');
    }

    private function updateExistingCart($cart, $quantity)
    {
        $cart->quantity = $quantity;
        $cart->amount = $cart->price * $cart->quantity;
        $cart->status = 'new';
        $cart->save();
    }

    private function createNewCart($request)
    {
        Cart::create([
            'user_id' => auth()->id(),
            'produk_id' => $request->produk_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'amount' => $request->amount,
            'status' => 'new'
        ]);
    }

    public function getSelectedItems()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $items = $cartItems->map(function ($item) {
            return [
                'id' => $item->produk_id,
                'name' => $item->product->nama_produk,
                'price' => $item->product->harga,
                'quantity' => $item->quantity,
                'discount' => $item->product->diskon,
                'image' => asset('storage/' . $item->product->gambar)
            ];
        });

        // Hitung summary
        $summary = [
            'totalQuantity' => $items->sum('quantity'),
            'totalPrice' => $items->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            }),
            'totalDiscount' => $items->sum(function ($item) {
                return ($item['price'] * $item['quantity'] * $item['discount']) / 100;
            }),
            'grandTotal' => $items->sum(function ($item) {
                return ($item['price'] * $item['quantity']) -
                    (($item['price'] * $item['quantity'] * $item['discount']) / 100);
            })
        ];

        return response()->json([
            'success' => true,
            'items' => $items,
            'summary' => $summary
        ]);
    }

    public function updateQuantity(Cart $cart, Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $cart->update([
                'quantity' => $request->quantity,
                'amount' => $cart->price * $request->quantity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quantity berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate quantity: ' . $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Cart $cart)
    {
        try {
            $cart->delete();
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
            ], 422);
        }
    }
}
