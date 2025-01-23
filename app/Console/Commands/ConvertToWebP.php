<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ConvertToWebP extends Command
{
    protected $signature = 'images:convert-webp';
    protected $description = 'Konversi semua gambar yang sudah terupload ke format WebP';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Folder tempat gambar disimpan
        $directory = 'uploads_gambar_produk';

        // Ambil semua file dari folder
        $files = Storage::disk('public')->files($directory);

        // Proses setiap file
        foreach ($files as $file) {
            // Cek apakah file memiliki ekstensi gambar yang valid
            if (preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                $this->info("Mengonversi: {$file}");

                $filePath = Storage::disk('public')->path($file);
                $outputPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $filePath);
                $outputName = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file);

                // Baca gambar dengan GD
                $image = null;
                if (preg_match('/\.jpg|\.jpeg$/i', $file)) {
                    $image = imagecreatefromjpeg($filePath);
                } elseif (preg_match('/\.png$/i', $file)) {
                    $image = imagecreatefrompng($filePath);
                }

                if ($image) {
                    // Simpan gambar dalam format WebP
                    imagewebp($image, $outputPath, 80); // Kualitas 80 (bisa diubah sesuai kebutuhan)
                    imagedestroy($image); // Hapus sumber daya gambar setelah diproses
                    $this->info("Selesai mengonversi: {$file} ke WebP");

                    // Update nama gambar di database
                    $this->updateDatabase($file, $outputName);
                } else {
                    $this->info("Gagal memproses gambar: {$file}");
                }
            } else {
                $this->info("Lewati file (bukan gambar): {$file}");
            }
        }

        $this->info('Semua gambar telah dikonversi ke WebP dan database berhasil diperbarui!');
    }

    // Fungsi untuk memperbarui nama gambar di database
    public function updateDatabase($oldFileName, $newFileName)
    {
        // Update data gambar di database
        DB::table('produk')
            ->where('gambar', $oldFileName) // Sesuaikan dengan nama kolom gambar di tabel Anda
            ->update(['gambar' => $newFileName]);

        $this->info("Nama file di database berhasil diperbarui: {$oldFileName} menjadi {$newFileName}");
    }
}