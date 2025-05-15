<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Produk; // Pastikan model Produk sudah ada
use App\Models\Pelanggan; // Pastikan model Pelanggan sudah ada
use App\Models\Berita; // Pastikan model Berita sudah ada
use App\Models\Transaksi; // Pastikan model Transaksi sudah ada
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        // Mengambil seluruh data promo
        $promos = Promo::all();

        // Mengembalikan view dengan data promo
        return view('admin.promos.index', compact('promos'));
    }

    public function showDashboard()
    {
        // Menghitung total produk, pelanggan, promo, berita
        $totalProduk = Produk::count();
        $totalPelanggan = Pelanggan::count();
        $totalPromo = Promo::count();
        $totalBerita = Berita::count();

        // Debugging: Periksa data yang dihitung
        dd($totalPromo, $totalProduk, $totalPelanggan, $totalBerita);

        // Mengambil 5 transaksi terbaru
        $transaksiTerbaru = Transaksi::latest()->take(5)->get();

        // Mengirim data ke view dashboard
        return view('admin.dashboard', compact('totalProduk', 'totalPelanggan', 'totalPromo', 'totalBerita', 'transaksiTerbaru'));
    }

    public function create()
    {
        // Menampilkan form untuk membuat promo baru
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        // Validasi input untuk promo baru
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric',
        ]);

        // Menyimpan data promo baru ke database
        Promo::create($request->all());

        // Redirect ke halaman index promo dengan pesan sukses
        return redirect()->route('admin.promos.index')->with('success', 'Promo created successfully.');
    }

    public function edit(Promo $promo)
    {
        // Menampilkan form untuk mengedit promo yang sudah ada
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        // Validasi input untuk memperbarui promo
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric',
        ]);

        // Memperbarui data promo yang sudah ada
        $promo->update($request->all());

        // Redirect ke halaman index promo dengan pesan sukses
        return redirect()->route('admin.promos.index')->with('success', 'Promo updated successfully.');
    }

    public function destroy(Promo $promo)
    {
        // Menghapus promo yang dipilih
        $promo->delete();

        // Redirect ke halaman index promo dengan pesan sukses
        return redirect()->route('admin.promos.index')->with('success', 'Promo deleted successfully.');
    }
}
