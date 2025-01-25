<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KategoriProdukController extends Controller
{
    // Begin CRUD Kategori Produk 
    public function indexKategori()
    {
        $kategori = DB::table('kategori_produk')->get()->map(function ($kat) { // Ambil semua kategori dari tabel kategori_produk
            return $kat;
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
            'slug' => 'required|string|max:200',
            'nama_kategori' => 'required|string|max:150',
            'deskripsi' => 'required|string',
        ]);

        DB::table('kategori_produk')->insert([
            'slug' => $request->slug,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori Produk berhasil ditambahkan.');
    }


    public function editKategori($id)
    {
        $kategori = DB::table('kategori_produk')->where('id', $id)->first(); // Gunakan 'id' bukan 'id'
        if (!$kategori) {
            abort(404);
        }

        return view('admin.kategori.edit', compact('kategori'));
    }


    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'slug' => 'required|string|max:200',
            'nama_kategori' => 'required|string|max:150',
            'deskripsi' => 'required|string',
        ]);

        DB::table('kategori_produk')->where('id', $id)->update([
            'slug' => $request->slug,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori Produk berhasil diperbarui.');
    }


    public function destroyKategori($id)
    {
        DB::table('kategori_produk')->where('id', $id)->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori Produk berhasil dihapus.');
    }
    // End CRUD Kategori Produk
}