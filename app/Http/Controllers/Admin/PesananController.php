<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PesananController extends Controller
{
    public function indexPesanan()
    {
        // Ambil semua pesanan dari database, tambahkan pagination jika diperlukan
        $pesanan = Orders::with('user')->latest()->paginate(10);

        // Kirim data ke view
        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function showPesanan($order_number)
    {
        $pesanan = Orders::with(['user', 'orderItems.produk'])->where('order_number', $order_number)->firstOrFail();

        return view('admin.pesanan.detail', compact('pesanan'));
    }

    public function destroyPesanan($id)
    {
        $pesanan = Orders::findOrFail($id);

        $pesanan->delete();

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}