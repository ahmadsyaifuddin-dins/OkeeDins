<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah email ada di database
        $user = DB::table('users')
            ->select('user_id', 'email', 'password')
            ->where('email', $request->email)
            ->first();

        if ($user && $user->password === $request->password) {
            // Login berhasil, simpan sesi
            auth()->loginUsingId($user->user_id); // login dengan user_id

            // Menambahkan pesan flash
            return redirect()->intended('/')->with('success', 'Login berhasil! Selamat datang di Market.');
        }

        // Jika gagal, kembali ke halaman login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
}
