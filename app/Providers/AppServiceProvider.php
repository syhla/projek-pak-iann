<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // ⬅️ Tambahkan ini!

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

// Contoh di AppServiceProvider boot method supaya semua view punya data ini:

public function boot()
{
    View::composer('*', function ($view) {
        $cart = session('cart', []);
        $totalQty = 0;
        foreach($cart as $item) {
            $totalQty += $item['quantity'];
        }
        $view->with('cartCount', $totalQty);
    });
}
}