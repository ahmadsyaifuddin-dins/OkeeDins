<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UlasanController;
use App\Models\KategoriProduk;
use App\Models\Produk;
use PHPUnit\Framework\Attributes\Group;

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
Route::get('/', [MarketController::class, 'index'])->middleware('cegah.admin.akses.pelanggan')->name('home.index');


// Route::get('/', [MarketController::class, 'index'])->name('home.index');
// Route::get('/cart', [MarketController::class, 'cart'])->name('market.cart');
// Route::get('/wishlist', [MarketController::class, 'wishlist'])->name('market.wishlist');
// Route::get('/profile', [MarketController::class, 'profile'])->name('profile');
// Route::get('/orders', [MarketController::class, 'orderHistory'])->name('market.orders');

// Market routes
Route::get('/produk/{slug}', [MarketController::class, 'detailProduk'])->name('produk.detail');
Route::get('/kategori/{slug}', [MarketController::class, 'index'])->name('market.kategori');

//! Route untuk guest (belum login) untuk pelanggan
Route::middleware('guest')->group(function () {
    Route::get('/login', [PelangganController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PelangganController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

//? Route Pelanggan
//! Route untuk pelanggan yang sudah login
Route::middleware(['auth', 'pelanggan'])->group(function () {  // Tambah middleware pelanggan
    Route::post('/logout', [PelangganController::class, 'logout'])->name('logout');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');


    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});


Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
Route::get('/checkout/pay-now', [CheckoutController::class, 'payNow'])->name('checkout.pay-now');


Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('/order/{id}/confirm-receipt', [OrderController::class, 'confirmReceipt'])->name('orders.confirm-receipt');
Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');


Route::patch('/cart/{cart}/quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/cart/get-selected-items', [CartController::class, 'getSelectedItems'])
    ->name('cart.get-selected-items');


// Route::middleware(['auth'])->group(function () {
//     Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
// Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// });

//? Route Admin

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'login'])->name('login');
        Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
        // Redirect guest to admin login
        Route::get('/dashboard', function () {
            return redirect()->route('admin.login');
        });
    });

    // Admin routes
    Route::middleware(['auth', 'adminOnly'])->group(function () {
        Route::get('/beranda', [AdminController::class, 'index'])->name('dashboard'); // Ini akan menjadi admin.dashboard
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        Route::prefix('')->group(function () {  // Tambahkan grup kosong untuk memastikan prefix nama benar

            Route::get('/pengguna', [AdminController::class, 'indexPengguna'])->name('pengguna.index'); // Halaman daftar pengguna
            Route::get('/kategori', [AdminController::class, 'indexKategori'])->name('kategori.index'); // Halaman daftar kategori
            Route::get('/produk', [AdminController::class, 'indexProduk'])->name('produk.index'); // Halaman daftar produk
            Route::get('/pesanan', [AdminController::class, 'indexPesanan'])->name('pesanan.index'); // Halaman daftar produk

            Route::get('/pesanan/{order_number}', [AdminController::class, 'showPesanan'])->name('pesanan.show');

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
            Route::delete('/pesanan/{id}', [AdminController::class, 'destroyPesanan'])->name('pesanan.destroy');


            Route::post('/pesanan/{id}/confirm', [OrderController::class, 'confirm'])->name('pesanan.confirm');
            Route::post('/pesanan/{id}/process', [OrderController::class, 'processing'])->name('pesanan.process');
            Route::post('/pesanan/{id}/delivery', [OrderController::class, 'delivery'])->name('pesanan.delivery');
            Route::post('/pesanan/{id}/complete', [OrderController::class, 'complete'])->name('pesanan.complete');
            Route::patch('/orders/{order}/confirm-cod', [OrderController::class, 'confirmCOD'])->name('orders.confirm-cod');


            Route::get('/pengguna/profile', [AdminController::class, 'profilePengguna'])->name('pengguna.profile'); // Halaman profile pengguna
        });
    });
    // Fallback untuk dashboard jika belum login
    // Route::get('/dashboard', function () {
    //     return redirect()->route('admin.login');
    // })->middleware('guest');
});