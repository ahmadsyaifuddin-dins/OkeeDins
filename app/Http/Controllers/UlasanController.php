<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'produk_id' => 'required|exists:produks,id',
            'rating' => 'required|integer|between:1,5',
            'ulasan' => 'required|string|max:1000',
        ]);

        Ulasan::create($validated);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}