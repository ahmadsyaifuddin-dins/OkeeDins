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
        $recommendedProducts = Produk::orderBy('recommended', 'desc')->take(9)->get();

        return view('home.index', compact('kategori', 'recommendedProducts'));
    }
}
