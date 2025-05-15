<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RekomendasiController;

// ===========================
// HALAMAN UTAMA (WELCOME)
// ===========================
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// ===========================
// CRUD PRODUK LANGSUNG DI WELCOME
// ===========================
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// ===========================
// AUTH: LOGIN / REGISTER
// ===========================
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// ===========================
// LOGOUT
// ===========================
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ===========================
// REDIRECT SETELAH LOGIN
// ===========================
Route::middleware('auth')->get('/home', function () {
    if (Auth::user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->hasRole('customer')) {
        return redirect()->route('customer.dashboard');
    }
    return redirect()->route('welcome');
});

// ===========================
// DASHBOARD
// ===========================
Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

Route::middleware(['auth', 'role:customer'])->get('/customer/dashboard', function () {
    return view('customer.dashboard');
})->name('customer.dashboard');

// ===========================
// HALAMAN UMUM
// ===========================
Route::get('/about', function () {
    return view('about');
})->name('about');

// ===========================
// ADMIN: PROMO, CATEGORY, SLIDE (TETAP DI ADMIN GROUP)
// ===========================
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Kategori
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Slide
    Route::post('/slides', [SlideController::class, 'store'])->name('slides.store');
    Route::get('/slides/{slide}/edit', [SlideController::class, 'edit'])->name('slides.edit');
    Route::put('/slides/{slide}', [SlideController::class, 'update'])->name('slides.update');
    Route::delete('/slides/{slide}', [SlideController::class, 'destroy'])->name('slides.destroy');

    // Rekomendasi
    Route::resource('rekomendasi', RekomendasiController::class);
});

// ===========================
// ADMIN: REKOMENDASI
// ===========================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('rekomendasi', RekomendasiController::class);
});
