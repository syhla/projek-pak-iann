<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Rekomendasi;
use App\Models\Slide;
use App\Models\Product;
use App\Models\Comment;
use App\Models\NewProduct;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    public function index()
    {
        // Produk yang hanya di-set tampil di halaman welcome (show_on_welcome = true)
        $products = Product::where('show_on_welcome', true)->get();

        // Ambil 3 kategori pertama
        $kategori_terbatas = Category::take(3)->get();

        // Ambil 4 produk rekomendasi
        $rekomendasi = Rekomendasi::take(4)->get();

        // Ambil semua gambar slide
        $slides = Slide::all();

        // Ambil komentar yang sudah disetujui, dengan relasi user
        $comments = Comment::with('user')
            ->where('status', 'Disetujui')
            ->latest()
            ->get();

        $newProducts = NewProduct::where('is_active', true)->latest()->take(6)->get();

         // Contoh, ambil jumlah produk di cart dari session atau database
    $cart = session()->get('cart', []);
    $cartCount = count($cart);

        // Kirim data ke view welcome
        return view('welcome', compact(
            'kategori_terbatas',
            'rekomendasi',
            'slides',
            'comments',
            'products',
            'newProducts',
            'cartCount'
        ));
    }
}
