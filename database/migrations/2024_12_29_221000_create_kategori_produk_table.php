<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriProdukTable extends Migration
{
    public function up()
    {
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 200)->unique();
            $table->string('nama_kategori', 175);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_produk');
    }
}
