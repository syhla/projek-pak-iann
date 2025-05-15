@extends('layouts.app')

@section('content')
<head>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
    <a href="{{ route('welcome') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
      Lihat Halaman Welcome
    </a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Kartu statistik -->
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-gray-600 text-sm">Total Produk</h2>
      <p class="text-2xl font-semibold text-indigo-600">{{ $totalProduk }}</p>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-gray-600 text-sm">Total Transaksi</h2>
      <p class="text-2xl font-semibold text-green-600">{{ $totalTransaksi }}</p>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-gray-600 text-sm">Total Pelanggan</h2>
      <p class="text-2xl font-semibold text-blue-600">{{ $totalPelanggan }}</p>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-gray-600 text-sm">Total Promo Saat Ini</h2>
      <p class="text-2xl font-semibold text-red-600">{{ $totalPromo }}</p>
    </div>
  </div>

  {{-- Transaksi Terbaru --}}
  <div class="bg-white shadow rounded-lg p-6 mb-10">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Transaksi Terbaru</h2>
    <ul>
      @forelse ($transaksiTerbaru as $transaksi)
        <li class="border-b border-gray-200 py-2">
          <span class="font-medium">{{ $transaksi->user->name }}</span> membeli
          <span class="text-indigo-600">{{ $transaksi->produk->name }}</span>
        </li>
      @empty
        <li class="text-gray-500">Belum ada transaksi.</li>
      @endforelse
    </ul>
  </div>

  {{-- Pesanan Custom Kue --}}
  <div class="bg-white shadow rounded-lg p-6 mb-10">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Pesanan Custom Kue ({{ $jumlahPesananBaru }} Belum Diproses)</h2>
    <ul>
      @forelse ($customOrders as $order)
        <li class="border-b border-gray-200 py-2">
          <div class="flex justify-between items-center">
            <div>
              <span class="font-medium">{{ $order->nama_pemesan }}</span> pesan <span class="text-pink-600">{{ $order->jenis_kue }}</span>
              <br><span class="text-sm text-gray-500">Status: {{ $order->status }}</span>
            </div>
            <div>
              <a href="{{ route('custom-orders.show', $order->id) }}" class="text-indigo-600 hover:underline text-sm">Detail</a>
            </div>
          </div>
        </li>
      @empty
        <li class="text-gray-500">Belum ada pesanan custom.</li>
      @endforelse
    </ul>
  </div>

  {{-- Grafik Transaksi --}}
  <div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Grafik Transaksi per Bulan</h2>
    <canvas id="transaksiChart"></canvas>
  </div>
</div>

@section('scripts')
<script>
  const bulan = @json($bulan);
  const jumlahTransaksi = @json($jumlahTransaksi);

  const ctx = document.getElementById('transaksiChart').getContext('2d');
  const transaksiChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: bulan,
      datasets: [{
        label: 'Jumlah Transaksi',
        data: jumlahTransaksi,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
@endsection
@endsection
