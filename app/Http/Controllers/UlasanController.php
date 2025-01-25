<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UlasanController extends Controller
{
    public function store(Request $request, Orders $order)
    {
        try {
            Log::info('Memulai proses pemberian ulasan untuk order: ' . $order->id);

            $validated = $request->validate([
                'rating' => 'required|integer|between:1,5',
                'ulasan' => 'required|string|max:1000',
            ]);

            // Dapatkan produk_id dari order item
            $orderItem = $order->orderItems->first();
            if (!$orderItem) {
                Log::error('Order item tidak ditemukan untuk order: ' . $order->id);
                return redirect()->back()->with('error', 'Data produk tidak ditemukan');
            }

            // Buat ulasan baru
            $ulasan = Ulasan::create([
                'user_id' => Auth::id(),
                'produk_id' => $orderItem->produk_id,
                'rating' => $validated['rating'],
                'ulasan' => $validated['ulasan'],
                'order_id' => $order->id
            ]);

            Log::info('Ulasan berhasil dibuat dengan ID: ' . $ulasan->id);

            return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan ulasan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan ulasan');
        }
    }
}