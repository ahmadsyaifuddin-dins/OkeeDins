<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('address_id');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 12, 2);
            $table->integer('qty');
            $table->enum('payment_method', ['Cash on Delivery', 'transfer', 'midtrans'])->default('Cash on Delivery');
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->string('status')->default('pending');
            $table->string('payment_proof', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
