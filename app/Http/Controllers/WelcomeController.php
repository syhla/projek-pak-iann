<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promo;
use App\Models\Slide;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Rekomendasi;

class WelcomeController extends Controller
{
    public function index()
    {
        $bestSellers = Product::where('is_best_seller', true)->get();
        $totalPromo = Promo::count();
        $slides = Slide::all();
        $categories = Category::all();
        $comments = Comment::latest()->take(5)->get();
        $rekomendasis = Rekomendasi::all();

        return view('welcome', compact(
            'bestSellers',
            'totalPromo',
            'slides',
            'comments',
            'categories',
            'rekomendasis'
        ));
    }

public function welcome()
{
    $comments = Comment::with('user')->latest()->paginate(5); // Ambil komentar terbaru dengan user

    return view('welcome', compact('comments'));
}
}
