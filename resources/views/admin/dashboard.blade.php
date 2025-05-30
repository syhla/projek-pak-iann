@extends('layouts.app')

@section('content')
<div class="p-8">
  <div x-data="{ tab: 'pending' }" class="bg-white rounded-xl shadow p-6 mb-10 border border-green-400">
  <div class="mb-4 border-b border-gray-200">
    <nav class="flex space-x-6" aria-label="Tabs">
      @php
        $tabs = [
          'pending' => ['label' => 'Menunggu', 'status' => 'pending'],
          'approved' => ['label' => 'Disetujui', 'status' => 'approved'],
          'rejected' => ['label' => 'Ditolak', 'status' => 'rejected'],
          'all' => ['label' => 'Semua', 'status' => null],
        ];

        $badgeColors = [
          'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu'],
          'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui'],
          'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
        ];
      @endphp

      @foreach ($tabs as $key => $data)
        <button
          @click="tab = '{{ $key }}'"
          :class="tab === '{{ $key }}'
            ? 'border-b-2 border-green-700 text-green-700'
            : 'border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
          class="py-2 px-3 text-sm font-medium whitespace-nowrap transition"
          type="button">
          {{ $data['label'] }}
        </button>
      @endforeach
    </nav>
  </div>

  <div class="overflow-x-auto max-h-[400px] overflow-y-auto border border-green-300 rounded-md shadow-inner">
    <table class="min-w-full divide-y divide-green-300 text-sm">
      <thead class="bg-gradient-to-r from-green-50 to-green-100 sticky top-0 z-10">
        <tr>
          <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Nama</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Komentar</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Status</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Aksi</th>
        </tr>
      </thead>

      @foreach ($tabs as $tabKey => $data)
        @php
          // Pastikan $comments sudah collection Eloquent, kalau tidak ambil dulu get()
          $filtered = is_null($data['status']) ? $comments : $comments->where('status', $data['status']);
        @endphp
        <tbody x-show="tab === '{{ $tabKey }}'" x-cloak>
          @forelse ($filtered as $komentar)
            @php
              $keyStatus = strtolower($komentar->status);
              $color = $badgeColors[$keyStatus] ?? $badgeColors['pending'];
            @endphp
            <tr class="hover:bg-green-50 transition">
              <td class="px-6 py-4 font-medium text-gray-700">{{ $komentar->user->nama ?? 'Anonim' }}</td>
              <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $komentar->pesan }}">{{ $komentar->pesan }}</td>
              <td class="px-6 py-4">
                <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full {{ $color['bg'] }} {{ $color['text'] }}">
                  {{ $color['label'] }}
                </span>
              </td>
              <td class="px-6 py-4">
                @if ($keyStatus === 'pending')
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
                @else
                  <span class="text-gray-400 italic text-xs">-</span>
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

  {{-- PESANAN TERBARU --}}
  <div class="bg-white rounded-xl shadow-lg p-6 mb-10 border border-green-300">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Pesanan Terbaru</h2>

    @if ($transaksiBaru->isEmpty())
      <p class="text-gray-500 italic">Belum ada pesanan.</p>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-green-100">
            <tr>
              <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">User</th>
              <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">Metode</th>
              <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">Status</th>
              <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">Tanggal</th>
              <th class="px-6 py-3 text-left font-semibold text-green-800 uppercase tracking-wide">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @foreach ($transaksiBaru as $order)
              <tr class="hover:bg-green-50 transition">
                <td class="px-6 py-4 font-medium text-gray-700">{{ $order->user->nama ?? 'Anonim' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ ucfirst($order->payment_method ?? '-') }}</td>
                <td class="px-6 py-4 text-gray-700 font-semibold">
                  {{ ucfirst($order->status) }}
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $order->created_at->diffForHumans() }}</td>
                <td class="px-6 py-4">
                  <form action="{{ route('admin.transactions.updateStatus', $order->id) }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    <select name="status" class="status-select border rounded px-2 py-1 text-sm" required>
                      <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="process" {{ $order->status == 'process' ? 'selected' : '' }}>Process</option>
                      <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                      <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

{{-- Pesanan Custom Baru di Dashboard --}}
<div class="bg-white rounded-xl shadow-lg p-6 border border-green-300">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Pesanan Custom Terbaru</h2>

  @if($pendingCustomOrders->isEmpty())
    <p class="text-gray-500 italic">Belum ada pesanan custom baru.</p>
  @else
    <div class="max-h-[400px] overflow-y-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-green-100">
          <tr>
            <th class="px-4 py-2 text-left font-semibold text-green-800 uppercase">Nama</th>
            <th class="px-4 py-2 text-left font-semibold text-green-800 uppercase">No WA</th>
            <th class="px-4 py-2 text-left font-semibold text-green-800 uppercase">Desain</th>
            <th class="px-4 py-2 text-left font-semibold text-green-800 uppercase">Status</th>
            <th class="px-4 py-2 text-left font-semibold text-green-800 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @foreach($pendingCustomOrders as $order)
            <tr class="hover:bg-green-50 transition">
              <td class="px-4 py-3 font-medium text-gray-700">{{ $order->nama }}</td>
              <td class="px-4 py-3 text-gray-600">{{ $order->no_wa }}</td>
              <td class="px-4 py-3 text-gray-600">{{ $order->desain }}</td>
              <td class="px-4 py-3">
                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                  {{ ucfirst($order->status) }}
                </span>
              </td>
              <td class="px-4 py-3 space-x-1">
                {{-- Tombol Setujui --}}
<form method="POST" action="{{ route('admin.custom.approve', $order->id) }}" class="inline">
  @csrf
  <button type="submit" onclick="return confirm('Setujui pesanan ini?');"
    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs">Setujui</button>
</form>

<form method="POST" action="{{ route('admin.custom.reject', $order->id) }}" class="inline">
  @csrf
  <button type="submit" onclick="return confirm('Tolak pesanan ini?');"
    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">Tolak</button>
</form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
      <div class="text-right mt-4">
      <a href="{{ route('admin.custom.index') }}"
        class="inline-block bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg shadow transition">
        Lihat Semua Pesanan
      </a>
    </div>

</div>

  {{-- NAVIGASI ADMIN --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
    @foreach ([
      ['label' => 'Kelola Kategori', 'route' => 'admin.categories.index'],
      ['label' => 'Kelola Produk', 'route' => 'admin.products.index'],
      ['label' => 'Produk Baru', 'route' => 'admin.newproducts.index'],
      ['label' => 'Laporan', 'route' => 'admin.laporan.index'],
    ] as $nav)
      <a href="{{ route($nav['route']) }}"
         class="bg-gradient-to-r from-green-700 to-yellow-500 text-white text-center py-4 rounded-xl shadow-lg font-semibold text-lg tracking-wide transform hover:scale-105 hover:shadow-2xl transition">
        {{ $nav['label'] }}
      </a>
    @endforeach
  </div>
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function () {
    $('.status-select').on('change', function () {
      const form = $(this).closest('form');
      form.submit();
    });
  });
</script>
@endsection
