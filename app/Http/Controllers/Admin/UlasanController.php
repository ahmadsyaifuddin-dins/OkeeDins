<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UlasanController extends Controller
{
    public function index()
    {
        // Ambil semua ulasan
        $ulasan = DB::table('ulasan')
            ->join('users', 'ulasan.user_id', '=', 'users.id')
            ->join('produk', 'ulasan.produk_id', '=', 'produk.id')
            ->select(
                'ulasan.*',
                'users.name as nama_pengguna',
                'users.photo as foto_pengguna',
                'produk.nama_produk',
                'produk.gambar as foto_produk'
            )
            ->orderBy('ulasan.created_at', 'desc')
            ->get();

        // Hitung statistik rating
        $total_rating = $ulasan->sum('rating');
        $total_ulasan = $ulasan->count();

        $statistik = [
            'total_ulasan' => $total_ulasan,
            'rata_rata_rating' => $total_ulasan > 0 ? $total_rating / $total_ulasan : 0,
            'rating_5' => $ulasan->where('rating', 5)->count(),
            'rating_4' => $ulasan->where('rating', 4)->count(),
            'rating_3' => $ulasan->where('rating', 3)->count(),
            'rating_2' => $ulasan->where('rating', 2)->count(),
            'rating_1' => $ulasan->where('rating', 1)->count(),
        ];

        // Data untuk grafik trend bulanan
        $bulanIni = now();
        $labels = [];
        $data = [];

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanIni->copy()->subMonths($i);
            $labels[] = $bulan->format('M Y');
            
            $jumlahUlasan = DB::table('ulasan')
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
            
            $data[] = $jumlahUlasan;
        }

        $statistik['labels'] = $labels;
        $statistik['data'] = $data;

        // Data untuk top produk
        $topProduk = Produk::withAvg('ulasan as rating_rata_rata', 'rating')
            ->withCount('ulasan as jumlah_ulasan')
            ->having('jumlah_ulasan', '>', 0)
            ->orderByDesc('rating_rata_rata')
            ->limit(5)
            ->get(); 

        return view('admin.ulasan.index', compact('ulasan', 'statistik', 'topProduk'));
    }

    public function show($id)
    {
        $ulasan = DB::table('ulasan')
            ->join('users', 'ulasan.user_id', '=', 'users.id')
            ->join('produk', 'ulasan.produk_id', '=', 'produk.id')
            ->select(
                'ulasan.*',
                'users.name as nama_pengguna',
                'users.email',
                'users.photo as foto_pengguna',
                'produk.nama_produk',
                'produk.gambar as foto_produk'
            )
            ->where('ulasan.id', $id)
            ->first();

        if (!$ulasan) {
            abort(404);
        }

        return view('admin.ulasan.show', compact('ulasan'));
    }

    public function destroy($id)
    {
        DB::table('ulasan')->where('id', $id)->delete();
        return redirect()->route('admin.ulasan.index')->with('success', 'Ulasan berhasil dihapus');
    }
}
