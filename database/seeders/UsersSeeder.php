<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                // 'password' => Hash::make('password123'), contoh yg pake hash bcrypt
                'password' => 'password123', // disini kita tidak menggunakan password bcrypt dulu
                'alamat' => 'Jl. Mawar No. 123',
                'tgl_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Laki-Laki',
                'telepon' => '081234567890',
                'makanan_fav' => 'Nasi Goreng',
                'role' => 'Pelanggan',
                'photo' => 'john_doe.jpg',
                'type_char' => 'Hero',
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => hash::make('password123'), // disini kita menggunakan password bcrypt
                'alamat' => 'Jl. Melati No. 456',
                'tgl_lahir' => '1995-05-15',
                'jenis_kelamin' => 'Perempuan',
                'telepon' => '081987654321',
                'makanan_fav' => 'Bakso',
                'role' => 'Administrator',
                'photo' => 'jane_smith.jpg',
                'type_char' => 'Villain',
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => str::random(10),
            ]
        ]);
    }
}
