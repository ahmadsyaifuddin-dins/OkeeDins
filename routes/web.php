<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\LoginController;
// use App\Http\Controllers\RegisterController;
// use App\Http\Controllers\ProfileController;
// /*
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider and all of them will
// | be assigned to the "web" middleware group. Make something great!
// |
// */

// Route::get('/', function () {
//     return view('market');
// })->name('market');

// // Route untuk menampilkan form registrasi
// Route::get('/register', [RegisterController::class, 'index'])->name('register');

// // Route untuk menangani data registrasi yang dikirimkan
// Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// // Route untuk memproses login
// Route::post('/login', [LoginController::class, 'login']);

// Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email');

// // Route untuk menampilkan form login
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');


// // Route untuk menampilkan dashboard admin
// Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');


// Route::middleware(['auth'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
//     Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
// });

// // Mengunci Halaman Login dan Register ketika pengguna telah logged
// Route::middleware('guest')->group(function () {
//     Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [LoginController::class, 'login']);
//     Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
//     Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
// });

// Route::middleware('auth')->group(function () {
//     Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
//     // Route lain yang memerlukan autentikasi
// });


// // Route Logout
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
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

Route::get('/', function () {
    return view('market');
})->name('market');

// Route untuk handle redirect setelah login
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('pelanggan.profile.show');
    }
    return redirect()->route('market');
})->name('home');

// Route untuk pengecekan email
Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email');

// Route untuk dashboard admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

// Route untuk profile pelanggan (perlu auth)
Route::middleware(['auth'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// Route yang memerlukan auth
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // Tambahkan route lain yang memerlukan autentikasi di sini
});
