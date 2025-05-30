<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
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
use App\Http\Controllers\Customer\TransactionController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\Customer\CustomerDashboardController;

/*
|--------------------------------------------------------------------------
| FRONTEND (Public)
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::view('/about', 'about')->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/promo', [PromoController::class, 'index'])->name('promo');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION (Guest only)
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
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| REDIRECT AFTER LOGIN (Role based)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/home', function () {
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('customer')) {
        return redirect()->route('customer.dashboard');
    }

    return redirect()->route('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Custom Cake Orders (Admin)
    Route::get('/custom', [CustomController::class, 'index'])->name('custom.index');
    Route::post('/custom/{id}/approve', [CustomController::class, 'approve'])->name('custom.approve');
    Route::post('/custom/{id}/reject', [CustomController::class, 'reject'])->name('custom.reject');

    // Produk, kategori, new products
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('newproducts', NewProductController::class)->except(['show']);

    // Slide management
    Route::post('/slides', [SlideController::class, 'store'])->name('slides.store');
    Route::get('/slides/{slide}/edit', [SlideController::class, 'edit'])->name('slides.edit');
    Route::put('/slides/{slide}', [SlideController::class, 'update'])->name('slides.update');
    Route::delete('/slides/{slide}', [SlideController::class, 'destroy'])->name('slides.destroy');

    // Komentar management
    Route::get('/komentar', [CommentController::class, 'index'])->name('komentar.index');
    Route::post('/komentar/{comment}/approve', [CommentController::class, 'approve'])->name('komentar.approve');
    Route::post('/komentar/{comment}/reject', [CommentController::class, 'reject'])->name('komentar.reject');

    // Laporan & Rekomendasi
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::resource('rekomendasi', RekomendasiController::class);

    // Transaksi
    Route::resource('transactions', TransaksiController::class)->only(['index', 'show']);
    Route::post('/transactions/{transaction}/update-status', [TransaksiController::class, 'updateStatus'])->name('transactions.updateStatus');

    // Promo management
    Route::get('/promos', [PromoController::class, 'adminIndex'])->name('promos.index');
    Route::get('/promos/create', [PromoController::class, 'create'])->name('promos.create');
    Route::post('/promos', [PromoController::class, 'store'])->name('promos.store');
    Route::get('/promos/{promo}/edit', [PromoController::class, 'edit'])->name('promos.edit');
    Route::put('/promos/{promo}', [PromoController::class, 'update'])->name('promos.update');
    Route::delete('/promos/{promo}', [PromoController::class, 'destroy'])->name('promos.destroy');

    // Orders management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');

    // Custom Cake customer
Route::prefix('custom')->name('custom.')->group(function () {
    Route::get('/', [CustomController::class, 'index'])->name('index');
    Route::get('/create', [CustomController::class, 'create'])->name('create');
    Route::post('/', [CustomController::class, 'store'])->name('store');
});

    // Komentar
    Route::get('/komentar/{status?}', [CommentController::class, 'index'])->name('komentar.index');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    // Keranjang & Checkout
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.form');
    Route::post('/checkout/page', [CheckoutController::class, 'checkoutPage'])->name('checkout.page');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/generate-qr', [App\Http\Controllers\QrCodeController::class, 'generate']);

});

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('welcome')->with('error', 'Halaman tidak ditemukan.');
});

