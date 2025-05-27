<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;

class TransactionController extends Controller
{
    public function index()
{
    $transactions = Transaksi::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('customer.transactions.index', compact('transactions'));
}

public function show($id)
{
    $transaksi = Transaksi::with(['items.product'])
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    // Hitung total harga dari semua item transaksi
    $totalHarga = $transaksi->items->reduce(function($carry, $item) {
        $harga = $item->product->harga ?? 0;
        $jumlah = $item->jumlah ?? 0;
        return $carry + ($harga * $jumlah);
    }, 0);

    return view('customer.transactions.show', compact('transaksi', 'totalHarga'));
}
}
