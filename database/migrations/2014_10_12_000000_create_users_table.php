<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');
            // $table->id();

            // Basic Information
            $table->string('name', 255);
            $table->string('email', 150)->unique();
            $table->string('password', 255);
            // $table->string('nik', 16)->nullable()->unique();

            // Profile Information
            $table->string('alamat', 255);
            // $table->string('kota', 100)->nullable();
            // $table->string('provinsi', 100)->nullable();
            // $table->string('kode_pos', 10)->nullable();
            // $table->text('bio')->nullable();
            $table->date('tgl_lahir');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->string('telepon', 20);
            $table->string('makanan_fav', 200);
            $table->string('photo', 255)->nullable();

            // Role & Character
            $table->enum('role', ['Pelanggan', 'Administrator'])->nullable();
            $table->enum('type_char', ['Hero', 'Villain']);

            // Security & Authentication
            // $table->boolean('two_factor_enabled')->default(false);
            // $table->string('two_factor_secret')->nullable();
            // $table->string('recovery_codes')->nullable();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
            // $table->boolean('is_active')->default(true);
            // $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip',45)->setDefault('0.0.0.0');

            // Social Media Links
            // $table->string('facebook_url')->nullable();
            // $table->string('instagram_url')->nullable();
            // $table->string('twitter_url')->nullable();

            // Membership & Points
            // $table->integer('points')->default(0);
            // $table->string('membership_level')->default('Bronze');
            // $table->timestamp('membership_expires_at')->nullable();

            // Preferences
            // $table->json('preferences')->nullable();

            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};