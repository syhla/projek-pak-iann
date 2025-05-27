<?php

namespace App\Http\Controllers;  // <== Tambahkan ini

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;

        if ($request->hasFile('image')) {
            $category->image = $request->file('image')->store('categories', 'public');
        }

        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $category->image = $request->file('image')->store('categories', 'public');
        }

        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function menuKategori()
    {
        $categories = Category::all();
        return view('menu', compact('categories'));
    }

public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        $categories = Category::all(); // untuk sidebar kategori

        $currentCategoryId = $category->id;

        return view('category', compact('category', 'categories', 'currentCategoryId'));
    }}
