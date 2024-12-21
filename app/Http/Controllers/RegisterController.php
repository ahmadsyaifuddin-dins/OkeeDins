<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showRegisterForm()
    {
        // Jika sudah login, alihkan ke halaman utama atau dashboard
        if (Auth::check()) {
            return redirect()->route('market')->with('message', 'Anda sudah login!');
        }

        return view('auth.register');
    }

    public function index()
    {
        return view('auth.register');
    }

    // Add new method to check email existence
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    public function store(Request $request)
    {
        // Log data untuk debugging
        Log::info('Data registrasi: ', $request->all());

        $validator = validator($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'telepon' => 'required',
            'makanan_fav' => 'required',
        ], [
            'email.unique' => 'Email sudah dipakai! Silakan gunakan email lain atau login dengan email yg terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Terdapat kesalahan pada data yang Anda masukkan.')
                ->withInput($request->except('password'));
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'alamat' => $request->alamat,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'telepon' => $request->telepon,
            'makanan_fav' => $request->makanan_fav,
            'role' => 'Pelanggan',
            'remember_token' => '',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login :)');
    }
}
