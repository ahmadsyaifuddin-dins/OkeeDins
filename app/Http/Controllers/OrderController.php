<?php

namespace App\Http\Controllers;

use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Orders::with(['orderItems.produk', 'user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $order = new Orders();
        $order->user_id = Auth::id();
        $order->payment_method = $request->payment_method;
        $order->status = $request->payment_method === 'Cash On Delivery' ? 'processing' : 'pending';
        $order->save();

        return redirect()->route('orders.confirmation', $order);
    }

    // Tambahkan method untuk admin mengkonfirmasi pembayaran COD
    public function confirmCOD(Orders $order)
    {
        if (Auth::user()->role !== 'Administrator') {
            abort(403);
        }
        $order->update([
            'status' => 'completed',
            'payment_status' => 'paid'
        ]);

        return back()->with('success', 'Pembayaran COD berhasil dikonfirmasi');
    }

    // OrderController.php
    public function confirmation(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.confirmation', compact('order'));
    }

    public function confirm($id)
    {
        $order = Orders::findOrFail($id);

        // Pastikan hanya pesanan COD dan status pending yang dapat dikonfirmasi
        if ($order->payment_method !== 'Cash on Delivery' || $order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan tidak valid untuk konfirmasi.');
        }

        $order->status = 'confirmed';
        $order->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi.');
    }

    public function processing($id)
    {
        $order = Orders::findOrFail($id);

        // Pastikan hanya pesanan COD dan status confirmed yang dapat diproses
        if ($order->payment_method !== 'Cash on Delivery' || $order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Pesanan tidak valid untuk diproses.');
        }

        $order->status = 'processing';
        $order->save();

        return redirect()->back()->with('success', 'Pesanan berhasil Diproses.');
    }

    /**
     * Set status pesanan menjadi 'delivered' setelah diproses.
     */
    public function delivery($id)
    {
        $order = Orders::findOrFail($id);

        // Pastikan hanya pesanan COD dan status processing yang dapat dikirim
        if ($order->payment_method !== 'Cash on Delivery' || $order->status !== 'processing') {
            return redirect()->back()->with('error', 'Pesanan tidak valid untuk dikirimkan.');
        }

        $order->status = 'delivered';
        $order->save();

        return redirect()->back()->with('success', 'Pesanan dalam pengiriman!');
    }

    public function confirmReceipt(Request $request, $id, $produk_id)
    {
        $order = Orders::findOrFail($id);
        $orderItems = OrderItems::findOrFail($produk_id);

        // Validasi input rating dan ulasan
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'ulasan' => 'required|string|max:1000',
        ]);

        // Pastikan hanya pesanan COD dan status delivered yang dapat dikonfirmasi
        if ($order->payment_method !== 'Cash on Delivery' || strtolower($order->status) !== 'delivered') {
            return redirect()->back()->with('error', 'Pesanan tidak valid untuk dikonfirmasi.');
        }

        // Update status pesanan
        $order->status = 'completed';
        $order->payment_status = 'paid';
        $order->save();

        // Simpan ulasan
        Ulasan::create([
            'user_id' => $order->user_id,
            'produk_id' => $orderItems->produk_id,
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Terima kasih telah mengkonfirmasi penerimaan pesanan dan memberikan ulasan.');
    }


    public function show(Orders $order)
    {
        // Memastikan user hanya bisa melihat pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function cancel(Orders $order)
    {
        // Memastikan user hanya bisa membatalkan pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya pesanan dengan status pending yang bisa dibatalkan
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        $order->update(['status' => 'cancelled']);

        // Kembalikan stok produk
        foreach ($order->orderItems as $item) {
            $item->produk->increment('stok', $item->quantity);
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan');
    }
}