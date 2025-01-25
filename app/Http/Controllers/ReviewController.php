<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Orders $order)
    {
        // Pastikan hanya pesanan yang completed yang bisa direview
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Hanya pesanan yang sudah selesai yang dapat direview');
        }

        // Pastikan user yang login adalah pemilik pesanan
        if ($order->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        // Ambil produk-produk dari order yang belum direview
        $products = $order->orderItems->map(function ($item) {
            return $item->produk;
        })->filter(function ($product) {
            // Cek apakah produk sudah direview oleh user
            return !Review::where('user_id', auth()->id())
                        ->where('produk_id', $product->id)
                        ->exists();
        });

        // Jika semua produk sudah direview, redirect ke halaman detail pesanan
        if ($products->isEmpty()) {
            return redirect()->route('orders.show', $order->id)
                ->with('info', 'Anda sudah memberikan ulasan untuk semua produk dalam pesanan ini');
        }

        return view('reviews.create', compact('order', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:10|max:255',
        ]);

        // Pastikan produk ada dalam pesanan yang completed
        $order = Orders::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereHas('orderItems', function ($query) use ($validated) {
                $query->where('produk_id', $validated['produk_id']);
            })
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Anda hanya bisa memberikan ulasan untuk produk yang sudah Anda beli');
        }

        // Pastikan belum ada review untuk produk ini dari user yang sama
        $existingReview = Review::where('user_id', auth()->id())
            ->where('produk_id', $validated['produk_id'])
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini');
        }

        // Buat review baru
        $review = new Review([
            'user_id' => auth()->id(),
            'produk_id' => $validated['produk_id'],
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'],
        ]);

        $review->save();

        // Cek apakah masih ada produk yang belum direview
        $remainingProducts = $order->orderItems->map(function ($item) {
            return $item->produk;
        })->filter(function ($product) {
            return !Review::where('user_id', auth()->id())
                        ->where('produk_id', $product->id)
                        ->exists();
        });

        if ($remainingProducts->isEmpty()) {
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Terima kasih! Anda telah memberikan ulasan untuk semua produk.');
        }

        return redirect()->route('reviews.create', $order->id)
            ->with('success', 'Terima kasih atas ulasan Anda! Anda masih memiliki beberapa produk yang belum diulas.');
    }
}
