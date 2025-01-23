<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {
        // Double check untuk memastikan yang akses adalah Administrator
        if (Auth::user()->role !== 'Administrator') {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai Administrator.');
        }

        return view('admin.dashboard');
    }

    public function profilePengguna()
    {
        // Double check untuk memastikan yang akses adalah Administrator
        if (Auth::user()->role !== 'Administrator') {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai Administrator.');
        }

        return view('admin.pengguna.profile');
    }


    public function login()
    {
        // Jika sudah login sebagai admin, redirect ke dashboard
        if (Auth::check() && Auth::user()->role === 'Administrator') {
            return redirect()->route('admin.dashboard'); // Kembali menggunakan admin.dashboard
        }

        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ditemukan dan rolenya Administrator
        if ($user && $user->role === 'Administrator') {
            // Cek password tanpa hashing
            if ($request->password === $user->password) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard'); // Kembali menggunakan admin.dashboard
            }

            return back()->withErrors([
                'password' => 'Password salah!',
            ]);
        }

        return back()->withErrors([
            'email' => 'Bukan Email untuk Administrator!',
        ])->withInput(); // Menyimpan input ke session
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}