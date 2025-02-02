<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('OkeDins');
            $table->string('app_logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('office_address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
