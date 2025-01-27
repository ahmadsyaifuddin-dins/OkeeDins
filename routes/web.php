<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\KategoriProdukController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\UlasanController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TransactionController;

use App\Http\Controllers\HomeController;
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
Route::get('/search', [MarketController::class, 'search'])->name('home.search');

// Route::get('/', [MarketController::class, 'index'])->name('home.index');
// Route::get('/cart', [MarketController::class, 'cart'])->name('market.cart');
// Route::get('/wishlist', [MarketController::class, 'wishlist'])->name('market.wishlist');
// Route::get('/profile', [MarketController::class, 'profile'])->name('profile');
// Route::get('/orders', [MarketController::class, 'orderHistory'])->name('market.orders');

// Market routes
Route::get('/produk/{slug}', [MarketController::class, 'detailProduk'])->name('produk.detail');
Route::get('/kategori/{slug}', [MarketController::class, 'index'])->name('market.kategori');

// Wishlist routes
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

//! Route untuk guest (belum login) untuk pelanggan
Route::middleware('guest')->group(function () {
    Route::get('/login', [PelangganController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PelangganController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});


//? Route Pelanggan

//! Route untuk pelanggan yang sudah login
Route::middleware(['auth', 'pelanggan'])->group(function () {
    Route::post('/logout', [PelangganController::class, 'logout'])->name('logout');

    // Riwayat dan Transaksi
    Route::get('/riwayat-pesanan', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/orders/{order}/detail', [OrderController::class, 'detail'])->name('orders.detail');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

    // Order
    Route::get('/orders', [OrderController::class, 'index'])->name('home.riwayat-pesanan');
    Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::get('/orders/{order}', [OrderController::class, 'detail'])->name('orders.detail');


    // Konfirmasi penerimaan dan rating
    Route::post('/orders/{order}/confirm-cod', [OrderController::class, 'confirmReceiptCOD'])->name('orders.confirm-cod');
    Route::post('/orders/{order}/confirm-transfer', [OrderController::class, 'confirmReceiptTransfer'])->name('orders.confirm-transfer');
    Route::post('/orders/{order}/review', [RatingController::class, 'store'])->name('orders.review');

    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Routes untuk pembayaran transfer
    Route::post('/orders/{order}/upload-payment', [OrderController::class, 'uploadPaymentProof'])->name('orders.upload-payment');
    Route::post('/orders/{order}/verify-payment', [OrderController::class, 'verifyPayment'])->name('orders.verify-payment');
    Route::post('/orders/{order}/reject-payment', [OrderController::class, 'rejectPayment'])->name('orders.reject-payment');

    Route::post('/ulasan', [RatingController::class, 'store'])->name('ulasan.store');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}/quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Voucher routes
    Route::post('/vouchers/validate', [CheckoutController::class, 'validateVoucher'])->name('vouchers.validate');

    // Payment Routes
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{order}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');

    // Address Routes
    Route::get('/addresses/list', [AddressController::class, 'getList'])->name('addresses.list');
    Route::get('/addresses/{address}', [AddressController::class, 'show'])->name('addresses.show');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::match(['PUT', 'PATCH'], '/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // Review routes
    Route::get('/reviews/create/{order}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});

Route::middleware('auth')->group(function () {

    // Address Management Routes
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::get('/addresses/{address}', [AddressController::class, 'show']);
    Route::match(['PUT', 'PATCH'], '/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
});

Route::get('/cart/get-selected-items', [CartController::class, 'getSelectedItems'])
    ->name('cart.get-selected-items');



//! Route Admin
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
        Route::get('/beranda', [DashboardController::class, 'index'])->name('dashboard'); // Ini akan menjadi admin.dashboard
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/pengguna/profile', [AdminController::class, 'profilePengguna'])->name('pengguna.profile'); // Halaman profile pengguna

        Route::prefix('')->group(function () {  // Tambahkan grup kosong untuk memastikan prefix nama benar

            Route::get('/pengguna', [PenggunaController::class, 'indexPengguna'])->name('pengguna.index'); // Halaman daftar pengguna
            Route::get('/pengguna/create', [PenggunaController::class, 'createPengguna'])->name('pengguna.create'); // Halaman form tambah pengguna
            Route::post('/pengguna', [PenggunaController::class, 'storePengguna'])->name('pengguna.store'); // Proses tambah pengguna
            Route::get('/pengguna/{id}', [PenggunaController::class, 'showPengguna'])->name('pengguna.show'); // Detail pengguna
            Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'editPengguna'])->name('pengguna.edit'); // Halaman form edit pengguna
            Route::put('/pengguna/{id}', [PenggunaController::class, 'updatePengguna'])->name('pengguna.update'); // Proses edit pengguna
            Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroyPengguna'])->name('pengguna.destroy'); // Proses hapus pengguna

            // Route untuk ulasan
            Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
            Route::get('/ulasan/{id}', [UlasanController::class, 'show'])->name('ulasan.show');
            Route::delete('/ulasan/{id}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');

            Route::get('/kategori', [KategoriProdukController::class, 'indexKategori'])->name('kategori.index'); // Halaman daftar kategori
            Route::get('/kategori/create', [KategoriProdukController::class, 'createKategori'])->name('kategori.create'); // Halaman form tambah kategori
            Route::post('/kategori', [KategoriProdukController::class, 'storeKategori'])->name('kategori.store'); // Proses tambah kategori
            Route::get('/kategori/{id}/edit', [KategoriProdukController::class, 'editKategori'])->name('kategori.edit'); // Halaman form edit kategori
            Route::put('/kategori/{id}', [KategoriProdukController::class, 'updateKategori'])->name('kategori.update'); // Proses edit kategori
            Route::delete('/kategori/{id}', [KategoriProdukController::class, 'destroyKategori'])->name('kategori.destroy'); // Proses hapus kategori

            Route::get('/produk', [ProdukController::class, 'indexProduk'])->name('produk.index'); // Halaman daftar produk
            Route::get('/produk/create', [ProdukController::class, 'createProduk'])->name('produk.create'); // Halaman form tambah produk
            Route::post('/produk', [ProdukController::class, 'storeProduk'])->name('produk.store'); // Proses tambah produk
            Route::get('/produk/{id}/edit', [ProdukController::class, 'editProduk'])->name('produk.edit'); // Halaman form edit produk
            Route::get('/produk/{id}', [ProdukController::class, 'showProduk'])->name('produk.show'); // Detail produk
            Route::put('/produk/{id}', [ProdukController::class, 'updateProduk'])->name('produk.update'); // Proses edit produk
            Route::delete('/produk/{id}', [ProdukController::class, 'destroyProduk'])->name('produk.destroy'); // Proses hapus produk

            Route::get('/pesanan', [PesananController::class, 'indexPesanan'])->name('pesanan.index'); // Halaman daftar produk
            Route::get('/pesanan/{order_number}', [PesananController::class, 'showPesanan'])->name('pesanan.show');
            Route::put('/pesanan/{id}/update-status', [OrderController::class, 'updateStatus'])->name('pesanan.updateStatus');
            Route::delete('/pesanan/{id}', [PesananController::class, 'destroyPesanan'])->name('pesanan.destroy');

            // Voucher Management
            Route::resource('vouchers', VoucherController::class);

            Route::post('/vouchers/validate', [CheckoutController::class, 'validateVoucher'])
                ->name('api.vouchers.validate');

            // Route::post('/pesanan/{id}/confirm', [OrderController::class, 'confirm'])->name('pesanan.confirm');
            // Route::post('/pesanan/{id}/process', [OrderController::class, 'processing'])->name('pesanan.process');
            // Route::post('/pesanan/{id}/delivery', [OrderController::class, 'delivery'])->name('pesanan.delivery');
            // Route::post('/pesanan/{id}/complete', [OrderController::class, 'complete'])->name('pesanan.complete');
            Route::patch('/orders/{order}/confirm-cod', [OrderController::class, 'confirmCOD'])->name('orders.confirm-cod');
            Route::patch('/orders/{order}/confirm-transfer', [OrderController::class, 'confirmTransfer'])->name('orders.confirm-transfer');

            // Route untuk pembayaran
            Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
            Route::post('/payments/{order}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verifyPayment'])->name('payments.verify');
            Route::post('/payments/{order}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'rejectPayment'])->name('payments.reject');

            Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
            Route::get('/laporan/export-excel', [App\Http\Controllers\Admin\LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
            Route::get('/laporan/export-pdf', [App\Http\Controllers\Admin\LaporanController::class, 'exportPDF'])->name('laporan.export-pdf');
        });
    });
});