<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua data dari tabel
        // DB::table('kategori_produk')->truncate();

        DB::table('kategori_produk')->insert([
            [
                'slug' => Str::slug('Makanan'),
                'nama_kategori' => 'Makanan',
                'deskripsi' => 'Produk makanan seperti makanan ringan, makanan berat, dan lainnya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Minuman'),
                'nama_kategori' => 'Minuman',
                'deskripsi' => 'Produk minuman seperti teh, kopi, jus, dan lainnya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Cemilan'),
                'nama_kategori' => 'Cemilan',
                'deskripsi' => 'Cemilan seperti keripik, biskuit, coklat, dan lainnya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Action Figure'),
                'nama_kategori' => 'Action Figure',
                'deskripsi' => 'Berbagai jenis action figure koleksi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Mainan'),
                'nama_kategori' => 'Mainan',
                'deskripsi' => 'Produk mainan untuk anak-anak maupun dewasa.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Fashion Pria'),
                'nama_kategori' => 'Fashion Pria',
                'deskripsi' => 'Produk pakaian dan aksesoris untuk pria.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Fashion Wanita'),
                'nama_kategori' => 'Fashion Wanita',
                'deskripsi' => 'Produk pakaian dan aksesoris untuk wanita.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Elektronik'),
                'nama_kategori' => 'Elektronik',
                'deskripsi' => 'Produk elektronik seperti televisi, komputer, dan perangkat lainnya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Buah-Buahan'),
                'nama_kategori' => 'Buah-Buahan',
                'deskripsi' => 'Berbagai jenis buah-buahan segar dan kering.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('Peralatan Rumah Tangga'),
                'nama_kategori' => 'Peralatan Rumah Tangga',
                'deskripsi' => 'Produk peralatan rumah tangga seperti alat masak, alat bersih-bersih, dan lainnya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}