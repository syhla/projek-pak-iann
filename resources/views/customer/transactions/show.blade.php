@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-3xl mx-auto p-8">
  <h1 class="text-4xl font-extrabold mb-8 text-green-700 border-b pb-4">Detail Transaksi #{{ $transaksi->id }}</h1>

  <div class="mb-6">
    <p><strong>Nama Pembeli:</strong> {{ $transaksi->user->name }}</p>
    <p><strong>No. HP:</strong> {{ $transaksi->no_hp }}</p>
    <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ ucfirst($transaksi->payment_method) }}</p>
    <p><strong>Metode Pengiriman:</strong> {{ ucfirst($transaksi->shipping_method) }}</p>
    <p><strong>Status:</strong> 
      <span class="px-2 py-1 rounded
        @if($transaksi->status == 'pending') bg-yellow-300 text-yellow-800
        @elseif($transaksi->status == 'completed') bg-green-300 text-green-800
        @elseif($transaksi->status == 'cancelled') bg-red-300 text-red-800
        @else bg-gray-300 text-gray-800 @endif
      ">
        {{ ucfirst($transaksi->status) }}
      </span>
    </p>
  </div>

  <table class="w-full border-collapse border border-gray-300 mb-6">
    <thead>
      <tr class="bg-green-100 text-green-900">
        <th class="border border-gray-300 p-3">Produk</th>
        <th class="border border-gray-300 p-3">Harga</th>
        <th class="border border-gray-300 p-3">Jumlah</th>
        <th class="border border-gray-300 p-3">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($transaksi->transaksiItems as $item)
      <tr class="text-center">
        <td class="border border-gray-300 p-3 text-left">{{ $item->product->nama ?? 'Produk tidak ditemukan' }}</td>
        <td class="border border-gray-300 p-3">Rp {{ number_format($item->product->harga ?? 0, 0, ',', '.') }}</td>
        <td class="border border-gray-300 p-3">{{ $item->jumlah }}</td>
        <td class="border border-gray-300 p-3">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="text-right text-xl font-bold text-green-800">
    Total Harga: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
  </div>

  <a href="{{ route('transaksi.index') }}" class="inline-block mt-6 px-6 py-3 bg-green-700 text-white rounded hover:bg-green-800 transition">
    Kembali ke Daftar Transaksi
  </a>
</div>
@endsection
