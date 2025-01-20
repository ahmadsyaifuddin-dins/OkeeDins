<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => 'admin123', // Tidak menggunakan bcrypt untuk contoh ini
             // 'password' => Hash::make('password123'), contoh yg pake hash bcrypt
                'alamat' => 'Jl. Mawar No. 123',
                'tgl_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Laki-Laki',
                'telepon' => '081234567890',
                'makanan_fav' => 'Nasi Goreng',
                'photo' => 'admin.jpg',
                'role' => 'Administrator',
                'type_char' => 'Villain',
                'last_login_ip' => '0.0.0.0', // Default IP address
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => 'password123',
                'alamat' => 'Jl. Anggrek No. 45',
                'tgl_lahir' => '1992-02-15',
                'jenis_kelamin' => 'Laki-Laki',
                'telepon' => '081223344556',
                'makanan_fav' => 'Sate Ayam',
                'photo' => 'john_doe.jpg',
                'role' => 'Pelanggan',
                'type_char' => 'Hero',
                'last_login_ip' => '0.0.0.0',
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Ahmad Syaifuddin',
                'email' => 'ahmads@gmail.com',
                'password' => 'ahmads123',
                'alamat' => 'Jl. Anggrek No. 45',
                'tgl_lahir' => '2004-06-09',
                'jenis_kelamin' => 'Laki-Laki',
                'telepon' => '081223344556',
                'makanan_fav' => 'Sate Ayam',
                'photo' => null,
                'role' => 'Pelanggan',
                'type_char' => 'Villain',
                'last_login_ip' => '0.0.0.0',
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => 'password123',
                'alamat' => 'Jl. Melati No. 456',
                'tgl_lahir' => '1995-05-15',
                'jenis_kelamin' => 'Perempuan',
                'telepon' => '081987654321',
                'makanan_fav' => 'Bakso',
                'photo' => 'jane_smith.jpg',
                'role' => 'Pelanggan',
                'type_char' => 'Villain',
                'last_login_ip' => '0.0.0.0',
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
            ]
        ]);
    }
}
