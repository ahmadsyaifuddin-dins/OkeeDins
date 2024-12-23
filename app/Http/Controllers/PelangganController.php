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
            // Cek jika user sedang di halaman profile
            if (request()->is('pelanggan/profile*')) {
                return redirect()->route('pelanggan.profile.show');
            }
            return redirect()->route('market');
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
            ->select('user_id', 'email', 'password')
            ->where('email', $request->email)
            ->first();

        if ($user && $user->password === $request->password) {
            // Login dengan Remember Me
            auth()->loginUsingId($user->user_id, $request->boolean('remember'));

            return redirect()->intended('/')
                ->with('success', 'Login berhasil! Selamat datang di Market');
        }


        // Jika gagal, kembali ke halaman login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
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