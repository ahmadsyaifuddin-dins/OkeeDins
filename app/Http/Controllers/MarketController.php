<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $kategori = KategoriProduk::all();

        // Get the selected category slug from the request
        $selectedKategoriSlug = $request->query('kategori');

        // Build the query for products
        $query = Produk::query();

        // If a category is selected, filter products by that category
        if ($selectedKategoriSlug) {
            $selectedKategori = KategoriProduk::where('slug', $selectedKategoriSlug)->firstOrFail();
            $query->where('kategori_id', $selectedKategori->id);
        }

        // Get recommended products with category filter if applied
        $recommendedProducts = $query->orderBy('recommended', 'desc')
            ->take(20)
            ->get();

        return view('home.index', compact('kategori', 'recommendedProducts', 'selectedKategoriSlug'));
    }

    public function detailProduk($slug)
    {
        $product = Produk::where('slug', $slug)->firstOrFail();
        return view('home.produk-detail', compact('product'));
    }
}