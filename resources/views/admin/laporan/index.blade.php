@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Penjualan</h1>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-lg">Total Transaksi: <strong>{{ $totalTransaksi }}</strong></p>
        <p class="text-lg">Total Pendapatan: <strong>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</strong></p>
    </div>
</div>
@endsection
