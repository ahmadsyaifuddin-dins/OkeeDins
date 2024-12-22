<?php


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

// Route untuk halaman utama (market)
Route::get('/', function () {
    return view('market');
})->name('market');

// Admin routes - LETAKKAN INI DI ATAS ROUTE GUEST
Route::prefix('admin')->name('admin.')->group(function () {
    // Halaman login admin (bisa diakses semua orang)
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');

    // Halaman yang butuh autentikasi admin
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    });
});

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// Route untuk user yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Route khusus pelanggan
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});
