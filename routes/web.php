<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;

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

//! Route untuk halaman utama (market)
Route::get('/', function () {
    // Cek status login dan role user
    if (auth()->check() && auth()->user()->role === 'Administrator') {
        return redirect()->route('admin.dashboard');
    }
    return view('market');
})->name('market');

//? Route Admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect jika tamu mencoba akses /admin/dashboard
    Route::middleware('guest')->get('/dashboard', function () {
        return redirect()->route('admin.login');
    });

    // Route untuk guest (belum login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'login'])->name('login');
        Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
    });

    // Route untuk admin yang sudah login
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard_administrator', [AdminController::class, 'index'])->name('dashboard'); // Halaman admin dashboard
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});

//! Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [PelangganController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PelangganController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

//? Route Pelanggan
//! Route untuk pelanggan yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [PelangganController::class, 'logout'])->name('logout');

    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});


// Route untuk profile pelanggan
Route::middleware(['auth', 'pelanggan'])->group(function () {  // Tambah middleware pelanggan
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});
