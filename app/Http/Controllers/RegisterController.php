<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Log data untuk debugging
        Log::info('Data registrasi: ', $request->all());

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'telepon' => 'required',
            'makanan_fav' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Tidak menggunakan bcrypt
            'alamat' => $request->alamat,
            'tgl_lahir' => $request->tgl_lahir,
            'telepon' => $request->telepon,
            'makanan_fav' => $request->makanan_fav,
            'role' => 'Pelanggan',
        ]);

        // Menyimpan pesan sukses di sesi flash 
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login :)');
    }
}
