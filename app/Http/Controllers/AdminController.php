<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

    public function editPengguna($user_id)
    {
        $pengguna = DB::table('users')->where('user_id', $user_id)->first(); // Gunakan 'user_id' bukan 'id'
        if (!$pengguna) {
            abort(404);
        }

        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function updatePengguna(Request $request, $user_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user_id, 'user_id')],
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
            $oldPhoto = DB::table('users')->where('user_id', $user_id)->value('photo');
            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

            // Simpan foto baru
            $photoPath = $request->file('photo')->store('uploads_photo_pelanggan', 'public');
            $updateData['photo'] = $photoPath;
        }

        try {
            DB::table('users')
                ->where('user_id', $user_id)
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

    public function destroyPengguna($user_id)
    {
        DB::table('users')->where('user_id', $user_id)->delete();
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
    // End CRUD Pengguna 


    // Begin CRUD Kategori Produk 
    public function indexKategori()
    {
        $kategori = DB::table('kategori_produk')->get()->map(function ($categori) { // Ambil semua kategori dari tabel kategori_produk
            return $categori;
        });
        return view('admin.kategori.index', compact('kategori'));
    }

    public function createKategori()
    {
        return view('admin.kategori.create'); // Tampilkan form tambah kategori
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:150',
            'deskripsi' => 'required|string',
        ]);

        DB::table('kategori_produk')->insert([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori Produk berhasil ditambahkan.');
    }


    public function editKategori($kategori_id)
    {
        $kategori = DB::table('kategori_produk')->where('kategori_id', $kategori_id)->first(); // Gunakan 'kategori_id' bukan 'id'
        if (!$kategori) {
            abort(404);
        }

        return view('admin.kategori.edit', compact('kategori'));
    }


    public function updateKategori(Request $request, $kategori_id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:150',
            'deskripsi' => 'required|string',
        ]);

        DB::table('kategori_produk')->where('kategori_id', $kategori_id)->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori Produk berhasil diperbarui.');
    }


    public function destroyKategori($kategori_id)
    {
        DB::table('kategori_produk')->where('kategori_id', $kategori_id)->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori Produk berhasil dihapus.');
    }
    // End CRUD Kategori Produk


    // Begin CRUD Produk
    public function indexProduk()
    {
        $produk = Produk::with('kategori')->get(); // Mengambil produk beserta kategori
        return view('admin.produk.index', compact('produk'));
    }

    public function createProduk()
    {
        $kategori = KategoriProduk::all(); // Ambil semua kategori
        return view('admin.produk.create', compact('kategori'));
    }


    // Menyimpan produk baru
    public function storeProduk(Request $request)
    {
        $request->validate([
            'gambar' => 'nullable|image|max:2048', // Validasi untuk gambar
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'nullable|exists:kategori_produk,kategori_id',
        ]);

        $gambarPath = $request->file('gambar')
            ? $request->file('gambar')->store('uploads_gambar_produk', 'public')
            : null;

        Produk::create([
            'gambar' => $gambarPath,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit produk
    public function editProduk($produk_id)
    {
        $produk = Produk::findOrFail($produk_id);
        $kategori = KategoriProduk::all();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    // Memperbarui produk
    public function updateProduk(Request $request, $produk_id)
    {
        $request->validate([
            'gambar' => 'nullable|image|max:2048', // Validasi untuk gambar
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'nullable|exists:kategori_produk,kategori_id',
        ]);

        $produk = Produk::findOrFail($produk_id);

        if ($request->file('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $gambarPath = $request->file('gambar')->store('uploads_gambar_produk', 'public');
        } else {
            $gambarPath = $produk->gambar;
        }

        $produk->update([
            'gambar' => $gambarPath,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // Menghapus produk
    public function destroyProduk($produk_id)
    {
        $produk = Produk::findOrFail($produk_id);
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
    // End CRUD Produk



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
