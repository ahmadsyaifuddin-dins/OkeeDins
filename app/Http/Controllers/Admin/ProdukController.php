<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
            'thumbnail' => 'required|image|max:2048', // akan disimpan ke field 'gambar'
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'diskon' => 'required|numeric|min:0|max:100',
            'kategori_id' => 'required',
            'recommended' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // Hitung harga setelah diskon
            $harga = str_replace('.', '', $request->harga);
            $desimalDiskon = $request->diskon / 100;
            $hargaSetelahDiskon = $harga - ($harga * $desimalDiskon);

            // Handle thumbnail/gambar utama
            $gambarPath = null;
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
                $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                $gambarPath = 'uploads_gambar_produk/' . $webpFilename;
                
                $directory = storage_path('app/public/uploads_gambar_produk');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Load and convert image to WebP
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if (!$image) {
                    throw new \Exception('Gagal membaca file gambar');
                }
                
                // Get original image dimensions
                $width = imagesx($image);
                $height = imagesy($image);
                
                // Create image with max dimension 800px
                $maxDimension = 800;
                if ($width > $maxDimension || $height > $maxDimension) {
                    if ($width > $height) {
                        $newWidth = $maxDimension;
                        $newHeight = ($height / $width) * $maxDimension;
                    } else {
                        $newHeight = $maxDimension;
                        $newWidth = ($width / $height) * $maxDimension;
                    }
                    
                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagewebp($resized, storage_path('app/public/' . $gambarPath), 80);
                    imagedestroy($resized);
                } else {
                    imagewebp($image, storage_path('app/public/' . $gambarPath), 80);
                }
                
                imagedestroy($image);
            }

            // Buat produk baru
            $produk = Produk::create([
                'slug' => $request->slug,
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'harga' => $harga,
                'stok' => $request->stok,
                'diskon' => $request->diskon,
                'harga_diskon' => $hargaSetelahDiskon,
                'kategori_id' => $request->kategori_id,
                'recommended' => $request->recommended ?? 0,
                'gambar' => $gambarPath, // Simpan path gambar ke field 'gambar'
            ]);

            DB::commit();
            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            if (isset($gambarPath)) {
                Storage::disk('public')->delete($gambarPath);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
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
            'diskon' => 'required|numeric|min:0|max:100|regex:/^\d+(\.\d{1,2})?$/',
            'kategori_id' => 'required',
            'recommended' => 'nullable',
        ]);

        $produk = Produk::findOrFail($id);
        $gambarPath = $produk->gambar;

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            $outputPath = 'uploads_gambar_produk/' . $webpFilename;
            
            // Create the directory if it doesn't exist
            $directory = storage_path('app/public/uploads_gambar_produk');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Load and convert image to WebP using GD
            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $outputFullPath = storage_path('app/public/' . $outputPath);
            imagewebp($image, $outputFullPath, 80); // 80 is the quality (0-100)
            imagedestroy($image);
            
            $gambarPath = $outputPath;
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

    public function showProduk($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        return view('admin.produk.show', compact('produk'));
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