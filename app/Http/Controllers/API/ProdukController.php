<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    public function getRecommendedProducts(Request $request)
    {
        try {
            $products = Cache::remember('recommended_products', 3600, function () {
                $products = Produk::with(['ulasan', 'order_items.order']) // Eager loading untuk menghindari N+1 problem
                    ->select([
                        'id',
                        'slug',
                        'nama_produk',
                        'gambar',
                        'harga',
                        'diskon',
                        'harga_diskon',
                        'stok'
                    ])
                    ->where('stok', '>', 0)
                    ->where('recommended', true)
                    ->take(8)
                    ->get()
                    ->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'slug' => $product->slug,
                            'nama_produk' => $product->nama_produk,
                            'gambar' => asset('storage/' . $product->gambar),
                            'harga' => $product->harga,
                            'diskon' => $product->diskon,
                            'harga_diskon' => $product->harga_diskon,
                            'rating' => number_format($product->rating, 1), // Menggunakan accessor yang sudah didefinisikan
                            'total_terjual' => $product->total_terjual, // Menggunakan accessor yang sudah didefinisikan
                            'formatted_harga' => 'Rp' . number_format($product->harga, 0, ',', '.'),
                            'formatted_harga_diskon' => 'Rp' . number_format($product->harga_diskon, 0, ',', '.')
                        ];
                    });
            });

            return response()->json([
                'success' => true,
                'data' => $products,
                'meta' => [
                    'cached' => Cache::has('recommended_products'),
                    'timestamp' => now()->timestamp
                ]
            ])->header('Cache-Control', 'public, max-age=1800')
                ->header('ETag', md5(json_encode($products)));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data produk',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Opsional: Endpoint untuk membatalkan cache jika ada update produk
    public function clearRecommendedCache()
    {
        try {
            Cache::forget('recommended_products');
            return response()->json(['message' => 'Cache successfully cleared']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to clear cache'], 500);
        }
    }
}