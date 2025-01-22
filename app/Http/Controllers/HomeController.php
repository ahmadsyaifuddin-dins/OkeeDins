<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Produk;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Produk::latest()->paginate(12);
        return view('home.index', compact('products'));
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
}
