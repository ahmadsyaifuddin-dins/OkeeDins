<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id'); // Primary key
            $table->string('gambar', 255)->nullable(); // Field gambar
            $table->string('nama_produk', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 0)->nullable();
            $table->integer('stok')->nullable();
            $table->integer('kategori_id')->nullable(); // Foreign key
            $table->timestamps(); // created_at and updated_at

            // Add foreign key constraint
            $table->foreign('kategori_id')->references('id')->on('kategori_produk')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}