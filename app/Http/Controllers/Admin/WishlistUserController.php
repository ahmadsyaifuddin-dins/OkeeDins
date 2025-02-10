<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistUserController extends Controller
{
    public function index()
    {
        // Mengambil produk yang paling banyak di-wishlist
        $topProducts = Wishlist::select('produk_id', DB::raw('count(*) as total'))
            ->with('produk')
            ->groupBy('produk_id')
            ->orderBy('total', 'desc')
            ->get();

        // Mengambil jumlah wishlist per user
        $userWishlists = Wishlist::select('user_id', DB::raw('count(*) as total'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->get();

        // Total semua wishlist
        $totalWishlists = Wishlist::count();

        return view('admin.wishlist.index', compact('topProducts', 'userWishlists', 'totalWishlists'));
    }


    public function show($userId)
    {
        $userWishlists = Wishlist::where('user_id', $userId)
            ->with(['user', 'produk'])
            ->get();

        $user = $userWishlists->first()->user;
        $totalWishlists = $userWishlists->count();

        return view('admin.wishlist.show', compact('userWishlists', 'user', 'totalWishlists'));
    }
}