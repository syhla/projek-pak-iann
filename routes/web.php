<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controller Imports
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\Admin\NewProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Frontend Routes (Untuk Semua Pengunjung)
|--------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::view('/about', 'about')->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/promo', [PromoController::class, 'index'])->name('promo');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Hanya untuk Guest)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Logout Route
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Redirect Setelah Login Berdasarkan Role
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/home', function () {
    if (Auth::user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->hasRole('customer')) {
        return redirect()->route('customer.dashboard');
    }
    return redirect()->route('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Hanya untuk Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Produk & Kategori
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Produk Baru
    Route::resource('newproducts', NewProductController::class)->except(['show']);

    // CRUD Slide Manual
    Route::post('/slides', [SlideController::class, 'store'])->name('slides.store');
    Route::get('/slides/{slide}/edit', [SlideController::class, 'edit'])->name('slides.edit');
    Route::put('/slides/{slide}', [SlideController::class, 'update'])->name('slides.update');
    Route::delete('/slides/{slide}', [SlideController::class, 'destroy'])->name('slides.destroy');

    // Komentar
    Route::get('/komentar', [CommentController::class, 'index'])->name('komentar.index');
    Route::post('/komentar/{comment}/approve', [CommentController::class, 'approve'])->name('komentar.approve');
    Route::post('/komentar/{comment}/reject', [CommentController::class, 'reject'])->name('komentar.reject');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Produk Rekomendasi
    Route::resource('rekomendasi', RekomendasiController::class);

    // Transaksi
    Route::get('/transactions', [TransaksiController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransaksiController::class, 'show'])->name('transactions.show');

    // CRUD Promo
    Route::get('/promos', [PromoController::class, 'adminIndex'])->name('promos.index');
    Route::get('/promos/create', [PromoController::class, 'create'])->name('promos.create');
    Route::post('/promos', [PromoController::class, 'store'])->name('promos.store');
    Route::get('/promos/{promo}/edit', [PromoController::class, 'edit'])->name('promos.edit');
    Route::put('/promos/{promo}', [PromoController::class, 'update'])->name('promos.update');
    Route::delete('/promos/{promo}', [PromoController::class, 'destroy'])->name('promos.destroy');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::post('/transactions/{id}/update-status', [TransaksiController::class, 'updateStatus'])->name('transactions.updateStatus');

});

/*
|--------------------------------------------------------------------------
| Customer Routes (Hanya untuk Customer)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::view('/customer/dashboard', 'customer.dashboard')->name('customer.dashboard');

    // Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    // Cart routes
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'checkoutPage'])->name('checkout.show'); 
    Route::post('/checkout', [CheckoutController::class, 'checkoutPage'])->name('checkout.page'); 
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process'); 
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm'); 
    Route::get('/checkout/thankyou', [CheckoutController::class, 'thankyou'])->name('checkout.thankyou'); 

    // Detail transaksi customer
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('transactions/{transaction}', [App\Http\Controllers\Customer\TransactionController::class, 'show'])->name('transactions.show');
});

});

