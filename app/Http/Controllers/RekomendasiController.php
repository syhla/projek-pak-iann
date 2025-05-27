<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\Category; // Jangan lupa import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RekomendasiController extends Controller
{
    public function index()
    {
        $rekomendasis = Rekomendasi::with('category')->get();
        $categories = Category::all();
        return view('rekomendasi.index', compact('rekomendasis', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rekomendasi_images', 'public');
        }

        Rekomendasi::create($data);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Rekomendasi $rekomendasi)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // hapus gambar lama jika ada
            if ($rekomendasi->image && Storage::disk('public')->exists($rekomendasi->image)) {
                Storage::disk('public')->delete($rekomendasi->image);
            }
            $data['image'] = $request->file('image')->store('rekomendasi_images', 'public');
        } else {
            // kalau gak upload image baru, tetap pakai gambar lama
            $data['image'] = $rekomendasi->image;
        }

        $rekomendasi->update($data);

        return redirect()->back()->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Rekomendasi $rekomendasi)
    {
        if ($rekomendasi->image && Storage::disk('public')->exists($rekomendasi->image)) {
            Storage::disk('public')->delete($rekomendasi->image);
        }

        $rekomendasi->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}
