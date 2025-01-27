<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Produk;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Data pendapatan hari ini
        $pendapatanHariIni = Orders::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // Data pendapatan minggu lalu untuk perbandingan
        $pendapatanMingguLalu = Orders::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfDay(),
                Carbon::now()->subWeek()->endOfDay()
            ])
            ->sum('total_amount');

        // Persentase perubahan pendapatan
        $persentasePendapatan = $pendapatanMingguLalu > 0 
            ? (($pendapatanHariIni - $pendapatanMingguLalu) / $pendapatanMingguLalu) * 100 
            : 100;

        // Data pengguna baru hari ini
        $penggunaBaru = User::whereDate('created_at', Carbon::today())->count();
        
        // Data pengguna bulan lalu untuk perbandingan
        $penggunaLalu = User::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        
        // Persentase perubahan pengguna
        $persentasePengguna = $penggunaLalu > 0 
            ? (($penggunaBaru - $penggunaLalu) / $penggunaLalu) * 100 
            : 100;

        // Data pesanan hari ini
        $pesananHariIni = Orders::whereDate('created_at', Carbon::today())->count();
        
        // Data pesanan kemarin untuk perbandingan
        $pesananKemarin = Orders::whereDate('created_at', Carbon::yesterday())->count();
        
        // Persentase perubahan pesanan
        $persentasePesanan = $pesananKemarin > 0 
            ? (($pesananHariIni - $pesananKemarin) / $pesananKemarin) * 100 
            : 100;

        // Data penjualan total
        $penjualanTotal = Orders::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->sum('total_amount');
            
        // Data penjualan kemarin untuk perbandingan
        $penjualanKemarin = Orders::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereDate('created_at', Carbon::yesterday())
            ->sum('total_amount');
            
        // Persentase perubahan penjualan
        $persentasePenjualan = $penjualanKemarin > 0 
            ? (($penjualanTotal - $penjualanKemarin) / $penjualanKemarin) * 100 
            : 100;

        // Data untuk tabel produk terlaris
        $produkTerlaris = Produk::with('kategori')
            ->withCount(['order_items as total_terjual' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', 'completed')
                        ->where('payment_status', 'paid');
                });
            }])
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Data untuk overview pesanan terbaru
        $pesananTerbaru = Orders::with('user')
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'pendapatanHariIni',
            'persentasePendapatan',
            'penggunaBaru',
            'persentasePengguna',
            'pesananHariIni',
            'persentasePesanan',
            'penjualanTotal',
            'persentasePenjualan',
            'produkTerlaris',
            'pesananTerbaru'
        ));
    }
}
