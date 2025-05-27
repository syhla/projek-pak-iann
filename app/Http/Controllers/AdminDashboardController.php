<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Transaksi;
use App\Models\CustomOrder;
use App\Models\Product;
use App\Models\User;
use App\Models\Promo;

class AdminDashboardController extends Controller
{
public function index()
{
    // Ambil komentar terbaru dengan relasi user
    $comments = Comment::with('user')->latest()->get();

    // Ambil transaksi baru dengan status 'Baru' atau 'Pending' (sesuaikan dengan status kamu)
    $transaksiBaru = Transaksi::with(['user', 'product'])->where('status', 'pending')->latest()->get();
    $jumlahTransaksiBaru = $transaksiBaru->count();

    // Ambil custom orders terbaru
    $customOrders = CustomOrder::latest()->take(5)->get();

    // Hitung jumlah pesanan custom baru (status = 'Belum Diproses')
    $jumlahPesananBaru = CustomOrder::where('status', 'Belum Diproses')->count();

    // Total pesanan custom
    $totalCustom = CustomOrder::count();

    // Total nominal transaksi
    $totalTransaksi = Transaksi::sum('total_harga'); // sesuaikan nama kolom

    // Contoh data transaksi per bulan (bulan 1 sampai 5)
    $bulan = [1, 2, 3, 4, 5];
    $jumlahTransaksi = [];
    foreach ($bulan as $month) {
        $jumlahTransaksi[] = Transaksi::whereMonth('created_at', $month)->count();
    }

    return view('admin.dashboard', compact(
        'comments',
        'transaksiBaru',
        'jumlahTransaksiBaru',
        'customOrders',
        'jumlahPesananBaru',
        'totalCustom',
        'totalTransaksi',
        'bulan',
        'jumlahTransaksi'
    ));
}
}
