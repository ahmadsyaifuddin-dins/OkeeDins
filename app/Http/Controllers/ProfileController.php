<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('pelanggan.profile.show');
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'tgl_lahir' => ['nullable', 'date'],
            'makanan_fav' => ['nullable', 'string', 'max:200'],
            'photo' => 'nullable|image|max:2048',
            'password' => ['nullable', 'string', 'min:8'], // Password baru
            // (pake hash password)
            // 'current_password' => ['required', 'string'], // Password lama
        ]);

        // Cek apakah password lama yang dimasukkan cocok  (pake hash password)
        // if (!Hash::check($request->current_password, $user->password)) {
        //     return back()->withErrors(['current_password' => 'Password lama tidak cocok']);
        // }


        // Proses perubahan foto profil
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('uploads_photo_pelanggan', 'public');
            $user->photo = $path;
        }

        // Hapus foto jika checkbox "Hapus foto profil" dicentang
        if ($request->has('remove_photo') && $request->remove_photo) {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $user->photo = null;
        }

        // Perbarui data pengguna
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->telepon = $validated['telepon'];
        $user->alamat = $validated['alamat'];
        $user->tgl_lahir = $validated['tgl_lahir'];
        $user->makanan_fav = $validated['makanan_fav'];

        // Perbarui password jika ada input password baru (pake hash password)
        // if ($request->filled('password')) {
        //     $user->password = Hash::make($validated['password']);
        // }

        // Perbarui password jika ada input password baru
        if ($request->filled('password')) {
            $user->password = $validated['password'];
        }

        if ($user instanceof User) {
            $user->save();
        } else {
            // Menangani error jika $user bukan instance dari User
        }
        // dd($user);

        return back()->with('success', 'Profile Berhasil disimpan!');
    }
}