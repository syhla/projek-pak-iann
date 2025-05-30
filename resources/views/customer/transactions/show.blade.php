@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-3xl mx-auto p-8">
  <h1 class="text-3xl font-bold mb-6 text-green-700 border-b pb-2">
    Detail Transaksi #{{ $transaksi->id }}
  </h1>

  {{-- Tanggal transaksi --}}
  <div class="mb-4 text-gray-700">
    <p><strong>Tanggal Transaksi:</strong> {{ $transaksi->created_at->format('d M Y H:i') }}</p>
  </div>

  {{-- Info pembeli dan transaksi --}}
  <div class="mb-6 text-gray-800 space-y-1">
    <p><strong>Nama Pembeli:</strong> {{ $transaksi->user->name }}</p>
    <p><strong>No. HP:</strong> {{ $transaksi->no_hp }}</p>
    <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ ucfirst($transaksi->payment_method) }}</p>
    <p><strong>Metode Pengiriman:</strong> {{ ucfirst($transaksi->shipping_method) }}</p>
    <p>
      <strong>Status:</strong>
      <span class="inline-block px-2 py-1 rounded
        @if($transaksi->status == 'pending') bg-yellow-300 text-yellow-800
        @elseif($transaksi->status == 'completed') bg-green-300 text-green-800
        @elseif($transaksi->status == 'cancelled') bg-red-300 text-red-800
        @else bg-gray-300 text-gray-800 @endif
      ">
        {{ ucfirst($transaksi->status) }}
      </span>
    </p>
  </div>

  {{-- Tabel item transaksi --}}
  <table class="w-full border border-gray-300 mb-6 text-gray-700">
    <thead class="bg-green-100 text-green-900">
      <tr>
        <th class="border border-gray-300 px-3 py-2 text-left">Produk</th>
        <th class="border border-gray-300 px-3 py-2 text-right">Harga</th>
        <th class="border border-gray-300 px-3 py-2 text-center">Jumlah</th>
        <th class="border border-gray-300 px-3 py-2 text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($transaksi->transaksiItems as $item)
      <tr>
<td class="border border-gray-300 px-3 py-2">{{ $item->product->name ?? 'Produk tidak ditemukan' }}</td>
<td class="border border-gray-300 px-3 py-2 text-right">
  Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }}
</td>
        <td class="border border-gray-300 px-3 py-2 text-center">{{ $item->jumlah }}</td>
        <td class="border border-gray-300 px-3 py-2 text-right">
          Rp {{ number_format($item->total_harga, 0, ',', '.') }}
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="text-center px-3 py-4 text-gray-500">Tidak ada item dalam transaksi ini.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Total harga --}}
  <div class="text-right text-xl font-semibold text-green-800 mb-4">
    Total Harga: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
  </div>

  {{-- Tombol kembali --}}
  <a href="{{ route('customer.dashboard') }}" 
     class="inline-block px-5 py-2 bg-green-700 text-white rounded hover:bg-green-800 transition">
    Kembali ke Dashboard
  </a>
</div>
@endsection
