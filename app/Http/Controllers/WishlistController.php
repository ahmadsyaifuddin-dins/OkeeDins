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
        $wishlists = Auth::user()->wishlists()->with('produk')->get();
        return view('wishlist.index', compact('wishlists'));
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
            return redirect()->back()->with('error', 'Product already in wishlist!');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id
        ]);

        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();

        return redirect()->back()->with('success', 'Product removed from wishlist successfully!');
    }
}
