<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewProduct;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class NewProductController extends Controller
{
    
    public function index()
    {
        $newProducts = NewProduct::with('category')->latest()->get();
        return view('admin.newproducts.index', compact('newProducts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.newproducts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('newproducts', 'public');
        }

        NewProduct::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            'is_today_available' => $request->has('is_today_available'),
            'is_menu_displayed' => $request->has('is_menu_displayed'),
            'is_new_product' => true,
        ]);

        return redirect()->route('admin.newproducts.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function update(Request $request, NewProduct $newproduct)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'category_id', 'price', 'stock']);
        $data['is_today_available'] = $request->has('is_today_available');
        $data['is_menu_displayed'] = $request->has('is_menu_displayed');

        if ($request->hasFile('image')) {
            if ($newproduct->image) {
                Storage::disk('public')->delete($newproduct->image);
            }
            $data['image'] = $request->file('image')->store('newproducts', 'public');
        }

        $newproduct->update($data);

        return redirect()->route('admin.newproducts.index')->with('success', 'Produk berhasil diperbarui!');
    }
}
