@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4" x-data="{ tab: 'pending' }">

  <!-- Komentar Card -->
  <div class="bg-white rounded-xl shadow p-6 mb-8 border border-green-400">
    <div class="mb-4 border-b border-gray-200">
      <nav class="-mb-px flex space-x-6" aria-label="Tabs">
        @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $key => $label)
          <button
            @click="tab = '{{ $key }}'"
            :class="tab === '{{ $key }}'
              ? 'border-b-2 border-[#556B2F] text-[#556B2F]'
              : 'border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="whitespace-nowrap py-2 px-3 font-medium text-sm">
            {{ $label }}
          </button>
        @endforeach
      </nav>
    </div>

    <div class="overflow-x-auto max-h-[400px] overflow-y-auto border border-green-300 rounded-md shadow-inner bg-white">
      <table class="min-w-full divide-y divide-green-300 text-sm">
        <thead class="bg-gradient-to-r from-[#F0FFF0] to-[#E0F2E9] sticky top-0 z-10">
          <tr>
            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Nama</th>
            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Komentar</th>
            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Status</th>
            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Aksi</th>
          </tr>
        </thead>

        @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $tabKey => $tabStatus)
          <tbody x-show="tab === '{{ $tabKey }}'" x-cloak>
            @php
              $filtered = $tabKey === 'all' ? $comments : $comments->where('status', $tabStatus);
            @endphp

            @forelse ($filtered as $komentar)
              <tr class="hover:bg-green-50 transition">
                <td class="px-6 py-4 font-medium text-gray-700">{{ $komentar->user->nama ?? 'Anonim' }}</td>
                <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $komentar->pesan }}</td>
                <td class="px-6 py-4">
                  @php
                    $colors = [
                      'Menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu'],
                      'Disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui'],
                      'Ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                    ];
                    $color = $colors[$komentar->status] ?? $colors['Menunggu'];
                  @endphp
                  <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $color['bg'] }} {{ $color['text'] }}">
                    {{ $color['label'] }}
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
                <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Tidak ada komentar.</td>
              </tr>
            @endforelse
          </tbody>
        @endforeach
      </table>
    </div>
  </div>

<!-- Card Pesanan Terbaru -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-green-300">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Pesanan Terbaru</h2>

  @if($transaksiBaru->isEmpty())
    <p class="text-gray-500 italic">Belum ada pesanan.</p>
  @else
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-green-100">
          <tr>
            <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">User</th>
            <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">Metode Pembayaran</th>
            <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">Status</th>
            <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">Tanggal</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @foreach($transaksiBaru as $order)
            <tr class="hover:bg-green-50 transition">
              <td class="px-6 py-4 font-medium text-gray-700">{{ $order->user->nama ?? 'Anonim' }}</td>
              <td class="px-6 py-4 text-gray-600">{{ ucfirst($order->payment_method ?? '-') }}</td>
              <td class="px-6 py-4">
                <form action="{{ route('admin.transactions.updateStatus', $order->id) }}" method="POST" class="inline-block">
                  @csrf
                  <select name="status" onchange="this.form.submit()"
                          class="border border-gray-200 rounded-md px-3 py-1 text-sm text-green-700 bg-gray-50
                                 focus:outline-none focus:ring-2 focus:ring-gray-400 transition">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
                </form>
              </td>
              <td class="px-6 py-4 text-gray-500">{{ $order->created_at->diffForHumans() }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

<!-- Navigasi -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
  @foreach([
    ['label' => 'Kelola Kategori', 'route' => 'admin.categories.index'],
    ['label' => 'Kelola Produk', 'route' => 'admin.products.index'],
    ['label' => 'Produk Baru', 'route' => 'admin.newproducts.index'],
    ['label' => 'Laporan', 'route' => 'admin.laporan.index'],
  ] as $nav)
    <a href="{{ route($nav['route']) }}"
       class="block bg-gradient-to-r from-green-700 to-yellow-500
              text-white text-center py-4 rounded-xl shadow-lg
              transform transition duration-300 hover:scale-105 hover:shadow-2xl
              font-semibold text-lg tracking-wide select-none">
      {{ $nav['label'] }}
    </a>
  @endforeach
</div>

</div>
@endsection
