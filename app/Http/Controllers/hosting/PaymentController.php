<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{

    public function index()
    {
        $orders = Orders::where('user_id', auth()->id())
            ->where('payment_method', 'transfer')
            ->whereIn('status', ['pending'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment.index', compact('orders'));
    }

    public function show(Orders $order)
    {
        // Memastikan user hanya bisa melihat pembayaran untuk ordernya sendiri
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payment.show', compact('order'));
    }

    public function confirm(Request $request, Orders $order)
    {
        // Validasi request
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Memastikan user hanya bisa mengkonfirmasi pembayaran untuk ordernya sendiri
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            // Upload bukti pembayaran
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/payment_proofs'), $filename);

                // Update status order
                $order->update([
                    'payment_proof' => $filename,
                    'status' => 'awaiting payment',
                    'payment_status' => 'pending'
                ]);

                return redirect()->route('orders.index')->with('success', 'Bukti pembayaran berhasil diunggah. Mohon tunggu konfirmasi dari admin.');
            }

            return back()->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}