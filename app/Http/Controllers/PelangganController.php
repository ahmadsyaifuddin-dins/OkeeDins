<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function __construct()
    {
        // Middleware auth untuk mencegah halaman login dan register diakses setelah login
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            // Jika user sudah login, arahkan sesuai role
            if (Auth::user()->role === 'Pelanggan') {
                return redirect()->route('market');
            } elseif (Auth::user()->role === 'Administrator') {
                return redirect()->route('admin.dashboard');
            }
        }
        return view('pelanggan.login');
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
            ->select('user_id', 'email', 'password', 'role')
            ->where('email', $request->email)
            ->first();

        if ($user) {
            // Jika user ditemukan, cek password dan role
            if ($request->password === $user->password) { // Sesuaikan hashing password jika menggunakan bcrypt
                if ($user->role === 'Pelanggan') {
                    // Login dengan Remember Me
                    Auth::loginUsingId($user->user_id, $request->boolean('remember'));
                    return redirect()->route('market.index')->with('success', 'Login berhasil! Selamat datang di Market');
                } elseif ($user->role === 'Administrator') {
                    return back()->withErrors([
                        'email' => 'Bukan Email untuk Pelanggan!',
                    ])->withInput();
                }
            }

            return back()->withErrors([
                'password' => 'Password salah!',
            ])->withInput();
        }

        // Jika user tidak ditemukan
        return back()->withErrors([
            'email' => 'Email tidak ditemukan!',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout');
    }
}
