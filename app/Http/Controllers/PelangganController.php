<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'Pelanggan') {
                return redirect()->route('home.index');
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

        // Gunakan model User daripada DB facade
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan!',
            ])->onlyInput('email');
        }

        // Cek role terlebih dahulu
        if ($user->role === 'Administrator') {
            return back()->withErrors([
                'email' => 'Bukan Email untuk Pelanggan!',
            ])->withInput();
        }

        // Verifikasi password
        if ($request->password !== $user->password) { // Sesuaikan dengan bcrypt jika menggunakan hashing
            return back()->withErrors([
                'password' => 'Password salah!',
            ])->withInput();
        }

        // Simpan IP address
        $user->last_login_ip = $request->ip();
        $user->save();

        // Login dengan Remember Me
        Auth::loginUsingId($user->id, $request->boolean('remember'));

        return redirect()
            ->route('home.index')
            ->with('success', 'Login berhasil! Selamat datang di Market');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout');
    }
}