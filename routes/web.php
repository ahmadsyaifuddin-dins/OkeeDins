<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
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
//TODO: php artisan route:clear

//! Route untuk halaman utama (market)
Route::get('/', function () {
    // Cek status login dan role user
    if (auth()->check() && auth()->user()->role === 'Administrator') {
        return redirect()->route('admin.dashboard');
    }
    return view('market.index');
})->name('market');

Route::get('/', [MarketController::class, 'index'])->name('market.index');
Route::get('/product/{id}', [MarketController::class, 'productDetail'])->name('market.product-detail');
Route::get('/category/{id}', [MarketController::class, 'category'])->name('market.category');
Route::get('/cart', [MarketController::class, 'cart'])->name('market.cart');
Route::get('/wishlist', [MarketController::class, 'wishlist'])->name('market.wishlist');
Route::get('/profile', [MarketController::class, 'profile'])->name('market.profile');
Route::get('/orders', [MarketController::class, 'orderHistory'])->name('market.orders');


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

// Tambahkan di dalam route admin yang memiliki middleware ['auth', 'admin']
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/pengguna', [AdminController::class, 'indexPengguna'])->name('pengguna.index'); // Halaman daftar pengguna
        Route::get('/kategori', [AdminController::class, 'indexKategori'])->name('kategori.index'); // Halaman daftar kategori
        Route::get('/produk', [AdminController::class, 'indexProduk'])->name('produk.index'); // Halaman daftar produk

        Route::get('/pengguna/create', [AdminController::class, 'createPengguna'])->name('pengguna.create'); // Halaman form tambah pengguna
        Route::get('/kategori/create', [AdminController::class, 'createKategori'])->name('kategori.create'); // Halaman form tambah kategori
        Route::get('/produk/create', [AdminController::class, 'createProduk'])->name('produk.create'); // Halaman form tambah produk

        Route::post('/pengguna', [AdminController::class, 'storePengguna'])->name('pengguna.store'); // Proses tambah pengguna
        Route::post('/kategori', [AdminController::class, 'storeKategori'])->name('kategori.store'); // Proses tambah kategori
        Route::post('/produk', [AdminController::class, 'storeProduk'])->name('produk.store'); // Proses tambah produk

        Route::get('/pengguna/{id}/edit', [AdminController::class, 'editPengguna'])->name('pengguna.edit'); // Halaman form edit pengguna
        Route::get('/kategori/{id}/edit', [AdminController::class, 'editKategori'])->name('kategori.edit'); // Halaman form edit kategori
        Route::get('/produk/{id}/edit', [AdminController::class, 'editProduk'])->name('produk.edit'); // Halaman form edit produk

        Route::put('/pengguna/{id}', [AdminController::class, 'updatePengguna'])->name('pengguna.update'); // Proses edit pengguna
        Route::put('/kategori/{id}', [AdminController::class, 'updateKategori'])->name('kategori.update'); // Proses edit kategori
        Route::put('/produk/{id}', [AdminController::class, 'updateProduk'])->name('produk.update'); // Proses edit produk

        Route::delete('/pengguna/{id}', [AdminController::class, 'destroyPengguna'])->name('pengguna.destroy'); // Proses hapus pengguna
        Route::delete('/kategori/{id}', [AdminController::class, 'destroyKategori'])->name('kategori.destroy'); // Proses hapus kategori
        Route::delete('/produk/{id}', [AdminController::class, 'destroyProduk'])->name('produk.destroy'); // Proses hapus produk

        Route::get('/pengguna/profile', [AdminController::class, 'profilePengguna'])->name('pengguna.profile'); // Halaman profile pengguna

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
