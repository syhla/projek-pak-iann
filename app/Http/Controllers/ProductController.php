<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer|min:0',
        'category_id' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->description = $request->description ?? '';
    $product->price = $request->price;
    $product->stock = $request->stock;
    $product->category_id = $request->category_id;
    $product->is_featured = $request->has('is_featured');
    $product->is_today_available = $request->has('is_today_available');
    $product->is_recommended = $request->has('is_recommended');
    $product->show_on_welcome = $request->has('show_on_welcome');

    if ($request->hasFile('image')) {
        $filename = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/products'), $filename);
        $product->image = 'uploads/products/' . $filename;
    }

    $product->save();

    return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
}

public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer|min:0',
        'category_id' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $product->name = $request->name;
    $product->description = $request->description ?? '';
    $product->price = $request->price;
    $product->stock = $request->stock;
    $product->category_id = $request->category_id;
    $product->is_featured = $request->has('is_featured');
    $product->is_today_available = $request->has('is_today_available');
    $product->is_recommended = $request->has('is_recommended');
    $product->show_on_welcome = $request->has('show_on_welcome');

    if ($request->hasFile('image')) {
        $filename = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/products'), $filename);
        $product->image = 'uploads/products/' . $filename;
    }

    $product->save();

    return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
}

    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
