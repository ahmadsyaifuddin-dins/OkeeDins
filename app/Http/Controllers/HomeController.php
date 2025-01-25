<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = KategoriProduk::all();
        $latestProducts = Produk::latest()->take(8)->get();

        return view('home.index', compact('categories', 'latestProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $products = Produk::query()
            ->when($query, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('kategori', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('home.search', [
            'products' => $products,
            'searchQuery' => $query
        ]);
    }


      public function riwayatPesanan()
    {
        return view('home.riwayat-pesanan');
    }

    public function transaksi()
    {
        return view('home.transaksi');
    }
}
