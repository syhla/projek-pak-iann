<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Promo;
use App\Models\CustomOrder; // Tambahkan ini
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Menghitung data utama
        $totalProduk = Product::count();
        $totalTransaksi = Transaksi::count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalPromo = Promo::count();

        // Transaksi terbaru (ambil user dan produk terkait)
        $transaksiTerbaru = Transaksi::with(['user', 'produk'])->latest()->take(5)->get();

        // Grafik Transaksi per Bulan
        $bulan = [1, 2, 3, 4, 5]; // Bisa diganti dinamis jika mau
        $jumlahTransaksi = [];

        foreach ($bulan as $month) {
            $jumlahTransaksi[] = Transaksi::whereMonth('created_at', $month)->count();
        }

        // ===== Tambahan: Pesanan Custom Kue =====
        $customOrders = CustomOrder::latest()->take(5)->get();
        $jumlahPesananBaru = CustomOrder::where('status', 'Belum Diproses')->count();

        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalProduk',
            'totalTransaksi',
            'totalPelanggan',
            'totalPromo',
            'transaksiTerbaru',
            'bulan',
            'jumlahTransaksi',
            'customOrders',
            'jumlahPesananBaru'
        ));
    }
}
