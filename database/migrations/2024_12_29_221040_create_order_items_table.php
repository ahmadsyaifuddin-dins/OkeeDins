<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('produk_id');
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('discount', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
