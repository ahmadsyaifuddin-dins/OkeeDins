<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    // Begin CRUD Pengguna 
    public function indexPengguna()
    {

        $pengguna = DB::table('users')->get()->map(function ($user) { // Ambil semua pengguna dari tabel users
            $user->photo = Storage::exists('public/' . $user->photo) ? $user->photo : 'user.svg';
            return $user;
        });
        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function createPengguna()
    {
        return view('admin.pengguna.create'); // Tampilkan form tambah pengguna
    }

    public function storePengguna(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:Pelanggan,Administrator,Kasir',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'telepon' => 'required',
            'makanan_fav' => 'required',
            'type_char' => 'required|in:Hero,Villain',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Proses upload foto terlebih dahulu jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('uploads_photo_pelanggan', 'public');
        }

        // Insert data dengan foto yang sudah diupload
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role ?? 'Pelanggan',
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'telepon' => $request->telepon,
            'makanan_fav' => $request->makanan_fav,
            'type_char' => $request->type_char,
            'photo' => $photoPath, // Gunakan path foto yang sudah diupload
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editPengguna($id)
    {
        $pengguna = DB::table('users')->where('id', $id)->first(); // Gunakan 'id' bukan 'id'
        if (!$pengguna) {
            abort(404);
        }

        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function updatePengguna(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($id, 'id')],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:Pelanggan,Administrator,Kasir',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'telepon' => 'required',
            'makanan_fav' => 'required',
            'type_char' => 'required|in:Hero,Villain',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Menyiapkan data untuk diupdate
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'telepon' => $request->telepon,
            'makanan_fav' => $request->makanan_fav,
            'type_char' => $request->type_char,
            'updated_at' => now(),
        ];

        // Handle password jika diisi
        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }

        // Handle photo
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            $oldPhoto = DB::table('users')->where('id', $id)->value('photo');
            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

            // Simpan foto baru
            $photoPath = $request->file('photo')->store('uploads_photo_pelanggan', 'public');
            $updateData['photo'] = $photoPath;
        }

        try {
            DB::table('users')
                ->where('id', $id)
                ->update($updateData);

            return redirect()
                ->route('admin.pengguna.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function destroyPengguna($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
    // End CRUD Pengguna 

}