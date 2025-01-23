<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class OptimizeUploadedImages extends Command
{
    protected $signature = 'images:optimize-uploaded';
    protected $description = 'Mengoptimalkan semua gambar produk yang sudah terupload';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Folder tempat menyimpan gambar
        $directory = 'uploads_gambar_produk';

        // Ambil semua file dari folder
        $files = Storage::disk('public')->files($directory);

        // Optimasi gambar satu per satu
        foreach ($files as $file) {
            // Cek apakah file memiliki ekstensi gambar yang valid
            if (preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                $this->info("Mengoptimalkan: {$file}");

                $optimizerChain = OptimizerChainFactory::create();
                $filePath = Storage::disk('public')->path($file);

                // Lakukan optimasi
                $optimizerChain->optimize($filePath);

                $this->info("Selesai mengoptimalkan: {$file}");
            } else {
                $this->info("Lewati file (bukan gambar): {$file}");
            }
        }

        $this->info('Semua gambar telah dioptimalkan!');
    }
}