<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Transaksi;
use App\Models\Custom;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ambil 5 komentar terbaru beserta relasi user
        $comments = Comment::with('user')->latest()->take(5)->get();

        // Ambil transaksi baru dengan status 'pending'
        $transaksiBaru = Transaksi::with(['user', 'transaksiItems.product'])
            ->where('status', 'pending')
            ->latest()
            ->get();
        $jumlahTransaksiBaru = $transaksiBaru->count();

        // Ambil 5 pesanan custom terbaru
        $customOrders = Custom::latest()->take(5)->get();

        // Hitung jumlah pesanan custom baru dengan status 'approved'
        $jumlahPesananBaru = Custom::where('status', 'approved')->count();

        // Ambil 5 pesanan custom terbaru dengan status 'menunggu' (pending approval)
        $pendingCustomOrders = Custom::where('status', 'menunggu')->latest()->take(5)->get();

        // Total nominal transaksi
        $totalTransaksi = Transaksi::sum('total_harga') ?? 0;

        // Total semua pesanan custom
        $totalCustom = Custom::count();

        // Data transaksi per bulan untuk grafik (contoh bulan 1 sampai 5)
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
            'pendingCustomOrders',
            'totalCustom',
            'totalTransaksi',
            'bulan',
            'jumlahTransaksi'
        ));
    }
}
