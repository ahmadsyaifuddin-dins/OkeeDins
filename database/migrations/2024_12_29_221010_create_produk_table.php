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
            $table->string('slug', 200)->unique();
            $table->string('gambar', 255)->nullable(); // Field gambar
            $table->string('nama_produk', 255)->nullable(); // Nama produk
            $table->text('deskripsi')->nullable(); // Deskripsi produk
            $table->decimal('harga', 10, 0)->nullable(); // Harga produk
            $table->double('diskon')->nullable(); // Diskon produk
            $table->double('harga_diskon')->nullable(); // Harga setelah diskon
            $table->integer('stok')->nullable(); // Stok produk
            $table->string('recommended', 50)->nullable(); // Produk recommended
            $table->unsignedBigInteger('kategori_id')->nullable(); // Foreign key
            $table->timestamps(); // created_at and updated_at

            // Add foreign key constraint
            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategori_produk')
                ->onDelete('set null'); // Set null on delete
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
