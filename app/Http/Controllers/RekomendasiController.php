<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekomendasi;
use Illuminate\Support\Facades\Storage;

class RekomendasiController extends Controller
{
    public function index()
    {
        $rekomendasis = Rekomendasi::all();
        return view('rekomendasi.index', compact('rekomendasis'));
    }

    public function create()
    {
        return view('rekomendasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('rekomendasi', 'public');
        }

        Rekomendasi::create($data);

        return redirect()->route('rekomendasi.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $rekomendasi = Rekomendasi::findOrFail($id);
        return view('rekomendasi.edit', compact('rekomendasi'));
    }

    public function update(Request $request, $id)
    {
        $rekomendasi = Rekomendasi::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($rekomendasi->gambar) {
                Storage::disk('public')->delete($rekomendasi->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('rekomendasi', 'public');
        }

        $rekomendasi->update($data);

        return redirect()->route('rekomendasi.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $rekomendasi = Rekomendasi::findOrFail($id);
        if ($rekomendasi->gambar) {
            Storage::disk('public')->delete($rekomendasi->gambar);
        }
        $rekomendasi->delete();
        return redirect()->route('rekomendasi.index')->with('success', 'Data berhasil dihapus');
    }
}
