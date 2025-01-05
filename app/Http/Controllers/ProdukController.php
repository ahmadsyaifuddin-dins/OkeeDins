<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function mostPopularProducts()
    {
        $produk = Produk::orderBy('popularity', 'desc') // Atau kriteria lain seperti penjualan
            ->take(10) // Batasi jumlah produk
            ->get();

        return view('most_popular_products', compact('produk'));
    }

    public function market()
    {
        $produk = Produk::orderBy('popularity', 'desc')->take(10)->get();

        return view('home.index', compact('produk'));
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'produk_id');
    }
}
