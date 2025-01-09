<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        $kategori = KategoriProduk::all();
        $recommendedProducts = Produk::orderBy('recommended', 'desc')->take(15)->get();

        return view('home.index', compact('kategori', 'recommendedProducts'));
    }

    public function detailProduk($slug)
    {
        $product = Produk::where('slug', $slug)->firstOrFail();

        return view('home.produk-detail', compact('product'));
    }
}