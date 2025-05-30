@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">📊 Laporan Penjualan</h1>

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label for="filter" class="block text-sm font-medium text-gray-700">Filter Waktu</label>
            <select name="filter" id="filter" class="mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="harian" {{ request('filter', $filter ?? '') == 'harian' ? 'selected' : '' }}>Harian</option>
                <option value="mingguan" {{ request('filter', $filter ?? '') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ request('filter', $filter ?? '') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow mt-6">
            Terapkan
        </button>
    </form>

    <!-- Ringkasan Total Penjualan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
        <div class="bg-gradient-to-r from-green-100 to-green-50 shadow rounded-2xl p-4">
            <h2 class="text-sm font-medium text-gray-700">Total Hari Ini</h2>
            <p class="text-xl font-bold text-blue-600 mt-1">{{ $totalHarian['transaksi'] ?? 0 }} Transaksi</p>
            <p class="text-sm text-green-700">Rp{{ number_format($totalHarian['pendapatan'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-r from-yellow-100 to-yellow-50 shadow rounded-2xl p-4">
            <h2 class="text-sm font-medium text-gray-700">Total Minggu Ini</h2>
            <p class="text-xl font-bold text-blue-600 mt-1">{{ $totalMingguan['transaksi'] ?? 0 }} Transaksi</p>
            <p class="text-sm text-green-700">Rp{{ number_format($totalMingguan['pendapatan'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-r from-blue-100 to-blue-50 shadow rounded-2xl p-4">
            <h2 class="text-sm font-medium text-gray-700">Total Bulan Ini</h2>
            <p class="text-xl font-bold text-blue-600 mt-1">{{ $totalBulanan['transaksi'] ?? 0 }} Transaksi</p>
            <p class="text-sm text-green-700">Rp{{ number_format($totalBulanan['pendapatan'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Ringkasan Berdasarkan Filter -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-700">
                Total Transaksi 
                @switch($filter)
                    @case('harian') Hari Ini @break
                    @case('mingguan') Minggu Ini @break
                    @case('bulanan') Bulan Ini @break
                    @default Semua Waktu
                @endswitch
            </h2>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $totalTransaksi ?? 0 }}</p>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-700">
                Total Pendapatan 
                @switch($filter)
                    @case('harian') Hari Ini @break
                    @case('mingguan') Minggu Ini @break
                    @case('bulanan') Bulan Ini @break
                    @default Semua Waktu
                @endswitch
            </h2>
            <p class="text-2xl font-bold text-green-600 mt-2">Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white shadow rounded-2xl p-6 mt-6 overflow-x-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Transaksi</h2>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Jenis</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($transaksis as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2">Transaksi</td>
                        <td class="px-4 py-2">{{ $item->nama ?? '-' }}</td>
                        <td class="px-4 py-2 text-green-600 font-semibold">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    {{-- Jika tidak ada transaksi --}}
                @endforelse

                @forelse ($customOrders as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2">Custom Order</td>
                        <td class="px-4 py-2">{{ $item->nama ?? '-' }}</td>
                        <td class="px-4 py-2 text-green-600 font-semibold">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    {{-- Jika tidak ada custom order --}}
                @endforelse
            </tbody>
        </table>

        @if ($transaksis->isEmpty() && $customOrders->isEmpty())
            <p class="text-gray-500 mt-4">Tidak ada data untuk ditampilkan.</p>
        @endif
    </div>
</div>
@endsection
