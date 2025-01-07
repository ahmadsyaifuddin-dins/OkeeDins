<?php

namespace App\Http\Controllers;

use App\Models\Orders;
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

        $order->update(['status' => 'Cancelled']);

        // Kembalikan stok produk
        foreach ($order->orderItems as $item) {
            $item->product->increment('stok', $item->quantity);
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan');
    }
}
