<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Auth::user()->wishlist()->with('produk')->get();
        return view('wishlist.index', compact('wishlist'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id'
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($wishlist) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'exists',
                    'message' => 'Product already in wishlist!'
                ], 409);
            }
            return redirect()->back()->with('error', 'Product already in wishlist!');
        }

        $wishlist = Wishlist::create([
            'user_id'   => Auth::id(),
            'produk_id' => $request->produk_id
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status'        => 'added',
                'message'       => 'Product added to wishlist successfully!',
                'wishlist_id'   => $wishlist->id,
                'wishlist_count' => Auth::user()->wishlist()->count() // Add this line
            ]);
        }

        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status'        => 'removed',
                'message'       => 'Product removed from wishlist successfully!',
                'wishlist_count' => Auth::user()->wishlist()->count() // Add this line
            ]);
        }

        return redirect()->back()->with('success', 'Product removed from wishlist successfully!');
    }
}