<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        return view('home.riwayat-pesanan', compact('orders'));
    }

    public function store(Request $request)
    {
        $order = new Orders();
        $order->user_id = Auth::id();
        $order->payment_method = $request->payment_method;
        $order->status = $request->payment_method === 'Cash on Delivery' ? 'processing' : 'pending';
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

    // Method untuk admin mengkonfirmasi pembayaran transfer
    public function confirmTransfer(Orders $order)
    {
        if (Auth::user()->role !== 'Administrator') {
            abort(403);
        }
        $order->update([
            'status' => 'confirmed',
            'payment_status' => 'paid'
        ]);

        return back()->with('success', 'Pembayaran transfer berhasil dikonfirmasi');
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

    public function updateStatus(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diupdate');
    }

    public function confirmReceiptCOD(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Update order status
            $order->update([
                'status' => 'completed',
                'payment_status' => 'paid'
            ]);

            // Proses ulasan
            $rating = app(RatingController::class)->store(request(), $order);

            // Update transaction
            $transaction = Transaction::where('order_id', $order->id)->first();
            if ($transaction) {
                $transaction->status = 'completed';
                $transaction->payment_status = 'paid';
                $transaction->payment_date = now(); // Set payment date saat pelanggan konfirmasi terima
                $transaction->save();
            }

            DB::commit();
            return back()->with('success', 'Pesanan berhasil dikonfirmasi dan ulasan telah disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming COD receipt: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengkonfirmasi penerimaan.');
        }
    }

    public function confirmReceiptTransfer(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Update order status
            $order->update([
                'status' => 'completed',
                'payment_status' => 'paid'
            ]);

            // Proses ulasan
            $rating = app(RatingController::class)->store(request(), $order);

            // Update transaction
            $transaction = Transaction::where('order_id', $order->id)->first();
            if ($transaction) {
                $transaction->status = 'completed';
                $transaction->payment_status = 'paid';
                $transaction->payment_date = now(); // Set payment date saat pelanggan konfirmasi terima
                $transaction->save();
            }

            DB::commit();
            return back()->with('success', 'Pesanan berhasil dikonfirmasi dan ulasan telah disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming transfer receipt: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengkonfirmasi penerimaan.');
        }
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

    public function history(Request $request)
    {
        $query = Orders::with(['orderItems.produk'])
            ->where('user_id', Auth::id());

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Add status label and color for each order
        $orders->each(function ($order) {
            $order->status_color = $this->getStatusColor($order->status);
        });

        return view('home.riwayat-pesanan', compact('orders'));
    }

    private function getStatusColor($status)
    {
        return [
            'pending' => 'warning',
            'awaiting payment' => 'warning',
            'confirmed' => 'info',
            'processing' => 'info',
            'delivered' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger'
        ][$status] ?? 'secondary';
    }

    public function track(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.track', compact('order'));
    }

    public function detail(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.detail', compact('order'));
    }

    // Method untuk upload bukti transfer
    public function uploadPaymentProof(Request $request, Orders $order)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payment_proofs'), $filename);

            $order->update([
                'payment_proof' => $filename,
                'status' => 'awaiting payment'
            ]);

            return redirect()->route('orders.detail', $order->id)->with('success', 'Bukti pembayaran berhasil diunggah');
        }

        return back()->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran');
    }

    // Method untuk admin memverifikasi pembayaran
    public function verifyPayment(Orders $order)
    {
        if (Auth::user()->role !== 'Administrator') {
            abort(403);
        }

        $order->update([
            'status' => 'processing',
            'payment_status' => 'paid'
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi');
    }

    // Method untuk menolak pembayaran
    public function rejectPayment(Orders $order)
    {
        if (Auth::user()->role !== 'Administrator') {
            abort(403);
        }

        $order->update([
            'status' => 'payment_rejected',
            'payment_status' => 'unpaid'
        ]);

        return back()->with('success', 'Pembayaran ditolak');
    }
}