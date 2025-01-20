<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->bigIncrements('id'); // id Primary Key, UNSIGNED, AUTO_INCREMENT
            $table->unsignedInteger('user_id'); // user_id Index, UNSIGNED
            $table->unsignedBigInteger('produk_id'); // produk_id Index, UNSIGNED
            $table->integer('quantity'); // quantity
            $table->decimal('price', 12, 2); // price
            $table->decimal('amount', 12, 2); // amount
            $table->string('status', 20)->default('new'); // status, varchar(50), Default 'new'
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart');
    }
};
