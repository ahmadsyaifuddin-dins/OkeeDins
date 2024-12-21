<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('market');
});

// Route untuk menampilkan form registrasi
Route::get('/register', [RegisterController::class, 'index'])->name('register');

// Route untuk menangani data registrasi yang dikirimkan
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email');

// Route untuk menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Route untuk memproses login
Route::post('/login', [LoginController::class, 'login']);

// Route untuk menampilkan dashboard admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
