<?php

namespace App\Http\Controllers\Admin;

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
        $produk = Produk::with('kategori')->paginate(10);
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
            'foto.*' => 'required|image|max:2048',
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

            $gambarPath = null;

            // Handle multiple images
            if ($request->hasFile('foto')) {
                $firstFile = $request->file('foto')[0]; // Ambil file pertama untuk thumbnail
                $filename = time() . '_' . $firstFile->getClientOriginalName();
                $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                $gambarPath = 'uploads_gambar_produk/' . $webpFilename;

                $directory = storage_path('app/public/uploads_gambar_produk');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Process thumbnail image
                $image = imagecreatefromstring(file_get_contents($firstFile->getRealPath()));
                if (!$image) {
                    throw new \Exception('Gagal membaca file gambar');
                }

                $width = imagesx($image);
                $height = imagesy($image);

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

            // Buat produk baru dengan gambar thumbnail
            $produk = Produk::create([
                'slug' => $request->slug,
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'harga' => $harga,
                'harga_diskon' => $hargaSetelahDiskon,
                'stok' => $request->stok,
                'diskon' => $request->diskon,
                'kategori_id' => $request->kategori_id,
                'recommended' => $request->recommended ? true : false,
                'gambar' => $gambarPath // Simpan path gambar thumbnail
            ]);

            // Simpan gambar tambahan
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $index => $file) {
                    if ($index === 0) continue; // Skip file pertama karena sudah dijadikan thumbnail

                    $filename = time() . '_' . $file->getClientOriginalName();
                    $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                    $gambarPath = 'uploads_gambar_produk/' . $webpFilename;

                    // Process additional images
                    $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                    if (!$image) {
                        throw new \Exception('Gagal membaca file gambar');
                    }

                    $width = imagesx($image);
                    $height = imagesy($image);

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

                    // Save additional image to database
                    ProductImage::create([
                        'produk_id' => $produk->id,
                        'image_path' => $gambarPath,
                        'is_thumbnail' => false
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
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
            'gambar.*' => 'nullable|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'diskon' => 'required|numeric|min:0|max:100|regex:/^\d+(\.\d{1,2})?$/',
            'kategori_id' => 'required',
            'recommended' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $produk = Produk::findOrFail($id);
            $gambarPath = $produk->gambar;

            // Fungsi untuk memproses gambar
            $processImage = function($file, $directory = 'uploads_gambar_produk') {
                $filename = time() . '_' . $file->getClientOriginalName();
                $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                $outputPath = $directory . '/' . $webpFilename;

                // Pastikan direktori ada
                $fullDirectory = storage_path('app/public/' . $directory);
                if (!file_exists($fullDirectory)) {
                    mkdir($fullDirectory, 0755, true);
                }

                // Baca dan proses gambar
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if (!$image) {
                    throw new \Exception('Gagal membaca file gambar');
                }

                // Resize jika diperlukan
                $width = imagesx($image);
                $height = imagesy($image);
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
                    
                    // Simpan gambar yang sudah diresize
                    imagewebp($resized, storage_path('app/public/' . $outputPath), 80);
                    imagedestroy($resized);
                } else {
                    // Simpan gambar tanpa resize
                    imagewebp($image, storage_path('app/public/' . $outputPath), 80);
                }

                imagedestroy($image);
                return $outputPath;
            };

            // Update gambar utama jika ada
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                    Storage::disk('public')->delete($produk->gambar);
                }
                
                $gambarPath = $processImage($request->file('gambar'));
            }

            // Update gambar tambahan jika ada
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $file) {
                    $additionalImagePath = $processImage($file);
                    
                    // Simpan ke database
                    ProductImage::create([
                        'produk_id' => $produk->id,
                        'image_path' => $additionalImagePath,
                        'is_thumbnail' => false
                    ]);
                }
            }

            // Hitung harga setelah diskon
            $harga = str_replace('.', '', $request->harga);
            $desimalDiskon = $request->diskon / 100;
            $hargaSetelahDiskon = $harga - ($harga * $desimalDiskon);

            // Update data produk
            $produk->update([
                'slug' => $request->slug,
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'gambar' => $gambarPath,
                'harga' => $harga,
                'stok' => $request->stok,
                'diskon' => $request->diskon,
                'harga_diskon' => $hargaSetelahDiskon,
                'kategori_id' => $request->kategori_id,
                'recommended' => $request->recommended ? true : false,
            ]);

            DB::commit();
            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function hapusFoto($id)
    {
        try {
            // Find the product image
            $productImage = ProductImage::findOrFail($id);

            // Delete the file from storage
            if ($productImage->image_path && Storage::disk('public')->exists($productImage->image_path)) {
                Storage::disk('public')->delete($productImage->image_path);
            }

            // Delete the record from database
            $productImage->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showProduk($id)
    {
        $produk = Produk::with('kategori', 'productImages')->findOrFail($id);
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
