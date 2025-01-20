<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produk')->insert([
            [
                'slug' => Str::slug('Smartphone XYZ Pro'),
                'gambar' => 'https://via.placeholder.com/300x300.png?text=Smartphone+XYZ+Pro',
                'nama_produk' => 'Smartphone XYZ Pro',
                'deskripsi' => 'Smartphone dengan performa tinggi, layar OLED 6.5 inci, dan kamera 108 MP.',
                'harga' => 7500000,
                'diskon' => 10, // Persentase diskon
                'harga_diskon' => 6750000, // Harga setelah diskon
                'stok' => 50,
                'recommended' => 'Ya',
                'kategori_id' => 1, // Asumsi kategori ID Elektronik
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Sneakers Sporty'),
                'gambar' => 'https://via.placeholder.com/300x300.png?text=Sneakers+Sporty',
                'nama_produk' => 'Sneakers Sporty',
                'deskripsi' => 'Sepatu olahraga yang nyaman dengan desain trendy dan daya tahan tinggi.',
                'harga' => 500000,
                'diskon' => 20, // Persentase diskon
                'harga_diskon' => 400000, // Harga setelah diskon
                'stok' => 100,
                'recommended' => 'Tidak',
                'kategori_id' => 2, // Asumsi kategori ID Fashion Pria
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Blender Multi-Function'),
                'gambar' => 'https://via.placeholder.com/300x300.png?text=Blender+Multi-Function',
                'nama_produk' => 'Blender Multi-Function',
                'deskripsi' => 'Blender serbaguna dengan 5 mode pengaturan kecepatan.',
                'harga' => 850000,
                'diskon' => 15, // Persentase diskon
                'harga_diskon' => 722500, // Harga setelah diskon
                'stok' => 25,
                'recommended' => 'Ya',
                'kategori_id' => 5, // Asumsi kategori ID Peralatan Rumah Tangga
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
