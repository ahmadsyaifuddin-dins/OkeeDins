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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id(); // Primary key dengan auto_increment
            $table->unsignedInteger('user_id'); // Foreign key ke tabel users
            $table->unsignedBigInteger('produk_id'); // Foreign key ke tabel produk
            $table->string('ulasan'); // Kolom ulasan
            $table->tinyInteger('rating'); // Kolom rating tanpa parameter panjang
            $table->timestamps(); // Kolom created_at dan updated_at

            // Definisi foreign key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Cascade delete
            $table->foreign('produk_id')
                ->references('id')
                ->on('produk')
                ->onDelete('cascade'); // Cascade delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};