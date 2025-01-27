<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LaporanPendapatanExport;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Produk;
use App\Models\Review;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        // Data untuk grafik pendapatan bulanan
        $pendapatanBulanan = Orders::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('SUM(total_amount) as total_pendapatan')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('bulan', 'tahun')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Data untuk produk terlaris
        $produkTerlaris = Produk::withCount(['order_items as total_terjual' => function ($query) {
            $query->whereHas('order', function ($q) {
                $q->where('status', 'completed')
                    ->where('payment_status', 'paid');
            });
        }])
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Statistik umum
        $statistik = [
            'total_pendapatan' => Orders::where('status', 'completed')
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'total_pesanan' => Orders::where('status', 'completed')
                ->where('payment_status', 'paid')
                ->count(),
            'total_produk' => Produk::count(),
            'rata_rata_rating' => Review::avg('rating') ?? 0
        ];

        // Format data untuk grafik
        $labels = [];
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create(null, $i, 1);
            $labels[] = $bulan->format('M');

            $pendapatan = $pendapatanBulanan->firstWhere('bulan', $i);
            $data[] = $pendapatan ? $pendapatan->total_pendapatan : 0;
        }

        return view('admin.laporan.index', compact('statistik', 'labels', 'data', 'produkTerlaris'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        return Excel::download(
            new LaporanPendapatanExport($startDate, $endDate),
            'laporan-pendapatan-' . Carbon::now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = Orders::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [
                $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth(),
                $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth()
            ])
            ->with(['orderItems.produk', 'user'])
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('data', 'startDate', 'endDate'));
        
        return $pdf->download('laporan-pendapatan-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
