<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\NewProduct; // jangan lupa import model NewProduct

class MenuController extends Controller
{
    // Menampilkan semua produk berdasarkan filter (untuk menu utama)
    public function index(Request $request)
    {
        $query = Product::with('category');

        if (!$request->has('filter') && !$request->has('category_id')) {
            $query->where('is_today_available', true);
        }

        if ($request->filter === 'available') {
            $query->where('is_today_available', true);
        }

        if ($request->filter === 'recommended') {
            $query->where('is_recommended', true);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        $categories = Category::all();

        // Ambil produk baru yang ditampilkan di menu
        $newProducts = NewProduct::where('is_new_product', true)
                                ->where('is_menu_displayed', true)
                                ->get();

        return view('menu', compact('products', 'categories', 'newProducts'));
    }

    // Menampilkan produk untuk kategori tertentu (akses lewat route menu/category/{id})
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products()->latest()->get();
        $categories = Category::all();

        // Produk baru
        $newProducts = NewProduct::where('is_new_product', true)
                                ->where('is_menu_displayed', true)
                                ->get();

        $currentCategoryId = $category->id;

        return view('menu', compact('category', 'products', 'categories', 'newProducts', 'currentCategoryId'));
    }
}
