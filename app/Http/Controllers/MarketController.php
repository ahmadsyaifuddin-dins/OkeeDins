<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class MarketController extends Controller
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

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Produk::query()
            ->with('kategori')
            ->join('kategori_produk', 'produk.kategori_id', '=', 'kategori_produk.id')
            ->select('produk.*')
            ->when($query, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('produk.nama_produk', 'like', "%{$search}%")
                        ->orWhere('produk.deskripsi', 'like', "%{$search}%")
                        ->orWhere('kategori_produk.nama_kategori', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Get wishlist items for the authenticated user
        $wishlistItems = [];
        if (auth()->check()) {
            $wishlistItems = auth()->user()->wishlist()->pluck('produk_id')->toArray();
        }

        return view('home.search', [
            'products' => $products,
            'searchQuery' => $query,
            'wishlistItems' => $wishlistItems
        ]);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'produk_id');
    }
}