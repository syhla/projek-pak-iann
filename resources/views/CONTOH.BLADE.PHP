@extends('layouts.app')

@section('content')
<head>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12 px-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 px-6">

    <div class="flex items-center justify-between mb-6">
    <h1 class="text-4xl font-bold text-gray-800">Dashboard Admin</h1>
    <a href="{{ route('welcome') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg mt0 hover:bg-indigo-700 transition">
      Lihat Halaman Welcome
    </a>
  </div>

{{-- resources/views/admin/dashboard.blade.php --}}
<div x-data="{ tab: 'pending' }" class="p-6 bg-white mb-9 rounded-lg shadow-md">
  <h1 class="text-2xl font-bold mb-6 text-gray-800">Daftar Komentar</h1>

  <!-- Tabs -->
  <div class="mb-4 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
      @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $key => $label)
        <button
          @click="tab = '{{ $key }}'"
          :class="tab === '{{ $key }}' ? 'border-[#D77A61] text-[#D77A61]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
          class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
          {{ $label }}
        </button>
      @endforeach
    </nav>
  </div>

  <!-- Table Wrapper -->
  <div class="overflow-x-auto max-h-[400px] overflow-y-auto border border-gray-200 rounded-md shadow-inner">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50 sticky top-0 z-10">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komentar</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
        </tr>
      </thead>

      <!-- Tab Menunggu -->
      <tbody x-show="tab === 'pending'" x-cloak>
        @forelse ($comments->where('status', 'Menunggu') as $komentar)
          <tr class="hover:bg-pink-50 transition">
            <td class="px-6 py-4 font-semibold text-gray-700">{{ $komentar->user->nama ?? 'Anonim' }}</td>
            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $komentar->pesan }}</td>
            <td class="px-6 py-4">
              <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
            </td>
            <td class="px-6 py-4">
              <div class="flex space-x-2">
                <form method="POST" action="{{ route('admin.komentar.approve', $komentar->id) }}">
                  @csrf
                  <button type="submit" onclick="return confirm('Setujui komentar ini?');"
                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs">Setujui</button>
                </form>
                <form method="POST" action="{{ route('admin.komentar.reject', $komentar->id) }}">
                  @csrf
                  <button type="submit" onclick="return confirm('Tolak komentar ini?');"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">Tolak</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Belum ada komentar yang menunggu.</td>
          </tr>
        @endforelse
      </tbody>

      <!-- Tab Disetujui -->
      <tbody x-show="tab === 'approved'" x-cloak>
        @forelse ($comments->where('status', 'Disetujui') as $komentar)
          <tr class="hover:bg-pink-50 transition">
            <td class="px-6 py-4 font-semibold text-gray-700">{{ $komentar->user->nama ?? 'Anonim' }}</td>
            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $komentar->pesan }}</td>
            <td class="px-6 py-4">
              <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
            </td>
            <td class="px-6 py-4"></td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Belum ada komentar yang disetujui.</td>
          </tr>
        @endforelse
      </tbody>

      <!-- Tab Ditolak -->
      <tbody x-show="tab === 'rejected'" x-cloak>
        @forelse ($comments->where('status', 'Ditolak') as $komentar)
          <tr class="hover:bg-pink-50 transition">
            <td class="px-6 py-4 font-semibold text-gray-700">{{ $komentar->user->nama ?? 'Anonim' }}</td>
            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $komentar->pesan }}</td>
            <td class="px-6 py-4">
              <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
            </td>
            <td class="px-6 py-4"></td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Belum ada komentar yang ditolak.</td>
          </tr>
        @endforelse
      </tbody>

      <!-- Tab Semua -->
      <tbody x-show="tab === 'all'" x-cloak>
        @forelse ($comments as $komentar)
          <tr class="hover:bg-pink-50 transition">
            <td class="px-6 py-4 font-semibold text-gray-700">{{ $komentar->user->nama ?? 'Anonim' }}</td>
            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $komentar->pesan }}</td>
            <td class="px-6 py-4">
              @php
                $status = $komentar->status;
                $colors = [
                  'Disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Approved'],
                  'Ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Rejected'],
                  'Menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu'],
                ];
              @endphp
              <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $colors[$status]['bg'] }} {{ $colors[$status]['text'] }}">
                {{ $colors[$status]['label'] }}
              </span>
            </td>
            <td class="px-6 py-4">
              @if($komentar->status === 'Menunggu')
                <div class="flex space-x-2">
                  <form method="POST" action="{{ route('admin.komentar.approve', $komentar->id) }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Setujui komentar ini?');"
                      class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs">Setujui</button>
                  </form>
                  <form method="POST" action="{{ route('admin.komentar.reject', $komentar->id) }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Tolak komentar ini?');"
                      class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">Tolak</button>
                  </form>
                </div>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Belum ada komentar.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
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

  <div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Kelola Produk</h2>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="bg-green-100 p-3 mb-4 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form tambah produk --}}
    <div class="bg-white p-6 rounded-2xl shadow mb-8">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Nama Produk</label>
                <input type="text" name="name" class="w-full border p-2 rounded" value="{{ old('name') }}" required>
                @error('name') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1">Deskripsi</label>
                <textarea name="description" class="w-full border p-2 rounded" rows="4">{{ old('description') }}</textarea>
                @error('description') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Harga</label>
                    <input type="number" name="price" class="w-full border p-2 rounded" value="{{ old('price') }}" required>
                    @error('price') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-1">Rating</label>
                    <input type="number" name="rating" min="0" max="5" step="0.1" class="w-full border p-2 rounded" value="{{ old('rating') }}">
                    @error('rating') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-1">Gambar</label>
                    <input type="file" name="image" class="w-full border p-2 rounded" required>
                    @error('image') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="flex items-center space-x-6 mt-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_today_available" value="1" {{ old('is_today_available') ? 'checked' : '' }}>
                    <span>Tersedia Hari Ini</span>
                </label>

                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }}>
                    <span>Rekomendasi</span>
                </label>
            </div>

            <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                Tambah Produk
            </button>
        </form>
    </div>

    {{-- Tabel produk --}}
    <div class="bg-white p-6 rounded-2xl shadow">
        <table class="w-full border-collapse border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border border-gray-300">Gambar</th>
                    <th class="p-3 border border-gray-300">Nama</th>
                    <th class="p-3 border border-gray-300">Harga</th>
                    <th class="p-3 border border-gray-300 text-center">Tersedia Hari Ini</th>
                    <th class="p-3 border border-gray-300 text-center">Rekomendasi</th>
                    <th class="p-3 border border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-t border-gray-300 hover:bg-gray-50">
                        <td class="p-2 border border-gray-300">
                            <img src="{{ asset('storage/' . $product->image) }}" width="80" class="rounded" alt="{{ $product->name }}">
                        </td>
                        <td class="p-2 border border-gray-300 font-semibold">{{ $product->name }}</td>
                        <td class="p-2 border border-gray-300">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-2 border border-gray-300 text-center">
                            {!! $product->is_today_available ? '✅' : '❌' !!}
                        </td>
                        <td class="p-2 border border-gray-300 text-center">
                            {!! $product->is_recommended ? '✅' : '—' !!}
                        </td>
                        <td class="p-2 border border-gray-300">
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">🗑 Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-600">Belum ada produk</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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
        backgroundColor: 'rgba(236, 72, 153, 0.2)', // pink-500 transparan
        borderColor: 'rgba(236, 72, 153, 1)',       // pink-500 solid
        borderWidth: 2,
        borderRadius: 6, // biar bar-nya rounded
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: {
            color: '#374151' // text-gray-700
          }
        }
      },
      scales: {
        x: {
          ticks: {
            color: '#6B7280' // text-gray-500
          },
          grid: {
            color: '#E5E7EB' // gray-200
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: '#6B7280'
          },
          grid: {
            color: '#E5E7EB'
          }
        }
      }
    }
  });
</script>
@endsection
@endsection



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'status',
    ];

    /**
     * Relasi ke User: satu transaksi dimiliki satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Product: satu transaksi terkait satu produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope untuk transaksi dengan status tertentu (opsional)
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}


// menu blade
@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
    $currentFilter = request('filter');
    $currentCategoryId = request()->route('categoryId') ?? request('category_id');
@endphp

<div class="flex bg-white p-6">

  <!-- Sidebar -->
  <div class="w-1/5 pr-4">
    <div class="border p-4 rounded-lg text-[#556B2F] font-semibold">
      <h2 class="text-lg mb-4">Sorted By</h2>
      <ul class="space-y-2">
        <li>
          <a href="{{ url()->current() }}?filter=available" 
             class="hover:text-[#D4AF37] {{ $currentFilter == 'available' ? 'font-bold text-[#D4AF37]' : '' }}">
            Available Product
          </a>
        </li>
        <li>
          <a href="{{ url()->current() }}?filter=recommended" 
             class="hover:text-[#D4AF37] {{ $currentFilter == 'recommended' ? 'font-bold text-[#D4AF37]' : '' }}">
            Recommended
          </a>
        </li>
      </ul>
    </div>

    <div class="border mt-6 p-4 rounded-lg text-[#556B2F] font-semibold">
      <h2 class="text-lg mb-4">Kategori</h2>
      <ul class="mt-4 space-y-2">
        @foreach($categories as $cat)
          <li>
            <a href="{{ route('menu.category', ['categoryId' => $cat->id]) }}"
               class="hover:text-[#D4AF37] {{ $currentCategoryId == $cat->id ? 'font-bold text-[#D4AF37]' : '' }}">
              {{ $cat->name }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  <!-- Konten Produk -->
  <div class="w-4/5">

    @isset($category)
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#556B2F]">{{ $category->name }}</h2>
        <p class="text-gray-600">{{ $category->description }}</p>
      </div>
    @endisset

    <div class="grid grid-cols-4 gap-6">
      @forelse($products as $produk)
        <div class="border rounded-lg shadow-md overflow-hidden">
          <img src="{{ asset($produk->image) }}" alt="{{ $produk->name }}" class="w-full h-40 object-cover">
          <div class="p-4 text-[#556B2F]">
            <h3 class="font-bold">{{ $produk->name }}</h3>
            <p class="line-through text-sm text-gray-400">
              Rp. {{ number_format($produk->original_price ?? ($produk->price + 5000), 0, ',', '.') }}
            </p>
            <p class="text-[#D4AF37] font-semibold">
              Rp. {{ number_format($produk->discount_price ?? $produk->price, 0, ',', '.') }}
            </p>
            <div class="mt-2 flex items-center text-yellow-500">
              ★★★★★
            </div>
            <button class="mt-3 w-full bg-[#D4AF37] text-white py-1 rounded">Add to Cart</button>
          </div>
        </div>
      @empty
        <div class="col-span-4 text-center text-gray-400 italic">
          Belum ada produk untuk kategori ini.
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection


// menu

@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
    $currentFilter = request('filter');
    $currentCategoryId = request()->route('categoryId') ?? request('category_id');
@endphp

<div class="flex bg-white p-6">

  <!-- Sidebar -->
  <div class="w-1/5 pr-4">
    <!-- Filter Sorted By -->
    <div class="border p-4 rounded-lg text-[#556B2F] font-semibold">
      <h2 class="text-lg mb-4">Sorted By</h2>
      <ul class="space-y-2">
        <li>
          <a href="{{ url()->current() }}?filter=available" 
             class="hover:text-[#D4AF37] {{ $currentFilter == 'available' ? 'font-bold text-[#D4AF37]' : '' }}">
            Available Product
          </a>
        </li>
        <li>
          <a href="{{ url()->current() }}?filter=recommended" 
             class="hover:text-[#D4AF37] {{ $currentFilter == 'recommended' ? 'font-bold text-[#D4AF37]' : '' }}">
            Recommended
          </a>
        </li>
      </ul>
    </div>

    <!-- Kategori -->
    <div class="border mt-6 p-4 rounded-lg text-[#556B2F] font-semibold">
      <h2 class="text-lg mb-4">Kategori</h2>
      <ul class="mt-4 space-y-2">
        @foreach($categories as $category)
          <li>
            <a href="{{ route('menu.category', ['categoryId' => $category->id]) }}"
               class="hover:text-[#D4AF37] {{ $currentCategoryId == $category->id ? 'font-bold text-[#D4AF37]' : '' }}">
              {{ $category->name }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  <!-- Konten Produk -->
  <div class="w-4/5 grid grid-cols-4 gap-6">
    @forelse($products as $produk)
      <div class="border rounded-lg shadow-md overflow-hidden">
        <img src="{{ asset('' . $produk->image) }}" alt="{{ $produk->name }}" class="w-full h-40 object-cover">
        <div class="p-4 text-[#556B2F]">
          <h3 class="font-bold">{{ $produk->name }}</h3>
          <p class="line-through text-sm text-gray-400">
            Rp. {{ number_format($produk->original_price ?? ($produk->price + 5000), 0, ',', '.') }}
          </p>
          <p class="text-[#D4AF37] font-semibold">
            Rp. {{ number_format($produk->discount_price ?? $produk->price, 0, ',', '.') }}
          </p>
          <div class="mt-2 flex items-center text-yellow-500">
            ★★★★★
          </div>
          <button class="mt-3 w-full bg-[#D4AF37] text-white py-1 rounded">Add to Cart</button>
        </div>
      </div>
    @empty
      <div class="col-span-4 text-center text-gray-400 italic">
        Belum ada produk yang ditampilkan untuk filter ini.
      </div>
    @endforelse
  </div>
</div>
@endsection
