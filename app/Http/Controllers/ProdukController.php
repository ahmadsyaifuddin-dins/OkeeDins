<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{

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
            'slug' => 'required|string|max:200',
            'gambar' => 'nullable|image|max:2048', // Validasi untuk gambar
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'diskon' => 'required|numeric|min:0|max:100|regex:/^\d+(\.\d{1,2})?$/', // Diskon dalam persen (0-100)
            'kategori_id' => 'required',
            'recommended' => 'nullable',
        ]);

        // Hilangkan titik pada harga untuk menyimpan angka murni
        $harga = str_replace('.', '', $request->harga);

        $gambarPath = $request->file('gambar')
            ? $request->file('gambar')->store('uploads_gambar_produk', 'public')
            : null;

        // Hitung harga setelah diskon
        $desimalDiskon = $request->diskon / 100; // Konversi ke float
        $hargaSetelahDiskon = $request->harga - ($request->harga * $desimalDiskon); // Hitung harga diskon

        Produk::create([
            'slug' => $request->slug,
            'gambar' => $gambarPath,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $harga,
            'stok' => $request->stok,
            'diskon' => $request->diskon, // Konversi eksplisit ke float
            'harga_diskon' => $hargaSetelahDiskon,
            'kategori_id' => $request->kategori_id,
            'recommended' => $request->recommended ?? 0, // Tambahkan nilai default jika tidak ada input

        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit produk
    public function editProduk($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = KategoriProduk::all();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    // Memperbarui produk
    public function updateProduk(Request $request, $id)
    {

        $request->validate([
            'slug' => 'required|string|max:200',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048', // Validasi untuk gambar
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'diskon' => 'required|numeric|min:0|max:100|regex:/^\d+(\.\d{1,2})?$/', // Memperbolehkan 2 angka desimal
            'kategori_id' => 'required',
            'recommended' => 'nullable',

        ]);


        $produk = Produk::findOrFail($id);

        if ($request->file('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $gambarPath = $request->file('gambar')->store('uploads_gambar_produk', 'public');
        } else {
            $gambarPath = $produk->gambar;
        }

        // Hitung harga setelah diskon
        $desimalDiskon = $request->diskon / 100; // Konversi ke float
        $hargaSetelahDiskon = $request->harga - ($request->harga * $desimalDiskon); // Hitung harga diskon

        $produk->update([
            'slug' => $request->slug,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'diskon' => $request->diskon,
            'harga_diskon' => $hargaSetelahDiskon,
            'kategori_id' => $request->kategori_id,
            'recommended' => $request->recommended ?? 0, // Tambahkan nilai default jika tidak ada input
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // Menghapus produk
    public function destroyProduk($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
    // End CRUD Produk
}