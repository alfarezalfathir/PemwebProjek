<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\LandingController; // (Opsional)
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CartController; 
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. HALAMAN UTAMA (PUBLIC)
// Kita arahkan langsung ke Login agar tidak error jika LandingController belum dibuat
Route::redirect('/', '/login');

// 2. GUEST (HANYA UNTUK YANG BELUM LOGIN)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. AUTHENTICATED (HARUS LOGIN DULU)
Route::middleware('auth')->group(function () {
    
    // --- UMUM (SEMUA USER) ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- FITUR BELANJA (CUSTOMER) ---
    // A. Halaman Menu Makanan (OrderController)
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');

    // B. Logic Keranjang & Checkout (CartController)
    Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('order.add');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::get('/remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // --- AREA KHUSUS STAF (ADMIN, MANAGER, KASIR) ---
    
    // A. Manajemen Master Data (Hanya Admin & Manager)
    Route::middleware(['role:superadmin|manager'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('tables', TableController::class);
    });

    // B. Manajemen User (Hanya Superadmin)
    Route::middleware(['role:superadmin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // C. Manajemen Transaksi/Kasir (Admin, Manager, & Kasir)
    Route::middleware(['role:superadmin|manager|cashier'])->group(function () {
        // Kita pakai Route::controller agar lebih rapi
        Route::controller(TransactionController::class)->group(function () {
            Route::get('/transactions', 'index')->name('transactions.index');
            Route::get('/transactions/{id}', 'show')->name('transactions.show');
            Route::post('/transactions/{id}/confirm', 'confirmPayment')->name('transactions.confirm');
            Route::get('/transactions/{id}/invoice', 'downloadInvoice')->name('transactions.invoice');
            
            // TAMBAHAN: Route untuk Hapus Transaksi
            Route::delete('/transactions/{id}', 'destroy')->name('transactions.destroy');
        });
    });

});