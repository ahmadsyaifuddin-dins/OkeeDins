<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id'); // Menyesuaikan tipe primary key sebagai int(11) auto increment
            $table->string('name', 255); // varchar(255) 
            $table->string('email', 150)->unique(); // varchar(150) unique
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255); // varchar(255) tidak nullable
            $table->string('alamat', 255); // varchar(255) tidak nullable
            $table->date('tgl_lahir'); // date, tidak nullable
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']); // enum tidak nullable
            $table->string('telepon', 20); // varchar(20) tidak nullable
            $table->string('makanan_fav', 200); // varchar(200), tidak nullable
            $table->enum('role', ['Pelanggan', 'Administrator', 'Kasir'])->nullable(); // enum nullable
            $table->string('photo', 255)->nullable(); // varchar(255) nullable
            $table->enum('type_char', ['Hero', 'Villain']); // enum, tidak nullable
            $table->string('remember_token', 255)->nullable(false); // varchar(255), tidak nullable
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps(); // created_at dan updated_at sebagai timestamp, tidak nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
