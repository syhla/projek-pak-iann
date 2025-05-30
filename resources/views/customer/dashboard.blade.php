@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
  <p class="text-4xl text-[#9a711f] mb-8">
    Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}!</span>
  </p>
@php
  $showQrId = request()->cookie('show_qr');
@endphp

@if ($showQrId)
  @php
    $showQrCustom = $customRequests->firstWhere('id', $showQrId);
  @endphp

  @if ($showQrCustom && $showQrCustom->status === 'disetujui' && $showQrCustom->qr_code)
    <div class="bg-green-100 border border-green-300 text-green-800 p-4 rounded shadow mb-6">
      <p class="font-semibold">Pesanan kamu disetujui! Scan QR ini untuk chat via WhatsApp 👇</p>
      <div class="mt-4 text-center">
        {!! QrCode::size(150)->generate($showQrCustom->qr_code) !!}
        <a href="{{ $showQrCustom->qr_code }}" target="_blank" class="block mt-2 text-green-700 font-semibold hover:underline">
          Klik untuk chat langsung
        </a>
      </div>

  @endif
@endif
    </div>

  {{-- Tabs --}}
  <div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs" id="tabs">
      <button data-tab="notif" class="tab-button border-b-2 border-green-700 text-green-700 py-2 px-4 font-semibold focus:outline-none">
        Notifikasi Pesanan Terbaru
      </button>
      <button data-tab="riwayat" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-2 px-4 font-semibold focus:outline-none">
        Riwayat Transaksi
      </button>
    </nav>
  </div>

  {{-- Notifikasi Pesanan --}}
  <section id="notif" class="tab-content">
    @php
      $latestOrders = Auth::user()->transactions()->with('items')->latest()->take(5)->get();
    @endphp

    @if($latestOrders->isEmpty())
      <p class="text-gray-500 italic">Belum ada notifikasi pesanan.</p>
    @else
      <ul class="space-y-4">
        @foreach($latestOrders as $order)
          @php
            $statusMessages = [
              'pending' => 'Pesanan Anda sedang diproses.',
              'completed' => 'Pesanan Anda sudah dikirim. Terima kasih!',
              'cancelled' => 'Pesanan Anda dibatalkan.',
            ];
            $colors = [
              'pending' => 'bg-yellow-100 text-yellow-800',
              'completed' => 'bg-green-100 text-green-800',
              'cancelled' => 'bg-red-100 text-red-800',
            ];
            $message = $statusMessages[$order->status] ?? 'Status pesanan tidak diketahui.';
            $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-800';
          @endphp

          <li class="border rounded p-4 {{ $color }} shadow-sm flex justify-between items-center">
            <div>
              <strong>Pesanan #{{ $order->id }}</strong> — <em>{{ $order->created_at->format('d M Y, H:i') }}</em><br>
              <span>{{ $message }}</span>
            </div>
            <div>
              <a href="{{ route('customer.transactions.show', $order->id) }}"
                 class="text-green-700 font-semibold hover:underline text-sm">Lihat Detail</a>
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  </section>

  {{-- Riwayat Transaksi --}}
  <section id="riwayat" class="tab-content hidden">
    @php
      $allOrders = Auth::user()->transactions()->with('items')->latest()->paginate(10);
    @endphp

    @if($allOrders->isEmpty())
      <p class="text-gray-500 italic">Belum ada transaksi.</p>
    @else
      <div class="overflow-x-auto rounded border border-gray-200 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-green-50">
            <tr>
              <th class="px-4 py-2 text-left font-semibold text-green-700 uppercase">ID Pesanan</th>
              <th class="px-4 py-2 text-left font-semibold text-green-700 uppercase">Tanggal</th>
              <th class="px-4 py-2 text-left font-semibold text-green-700 uppercase">Status</th>
              <th class="px-4 py-2 text-left font-semibold text-green-700 uppercase">Total</th>
              <th class="px-4 py-2"></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @foreach($allOrders as $order)
              @php
                $statusColors = [
                  'pending' => 'text-yellow-600',
                  'completed' => 'text-green-600',
                  'cancelled' => 'text-red-600',
                ];
                $totalHarga = $order->items ? $order->items->sum('total_harga') : 0;
              @endphp
              <tr class="hover:bg-green-50">
                <td class="px-4 py-2 font-medium">#{{ $order->id }}</td>
                <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                <td class="px-4 py-2 font-semibold {{ $statusColors[$order->status] ?? '' }}">{{ ucfirst($order->status) }}</td>
                <td class="px-4 py-2 text-right font-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                <td class="px-4 py-2 text-right">
                  <a href="{{ route('customer.transactions.show', $order->id) }}"
                     class="text-green-700 hover:underline font-semibold text-sm">Detail</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $allOrders->links() }}
      </div>
    @endif
  </section>
</div>

{{-- Script tab switching --}}
<script>
  document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
      const tab = button.dataset.tab;

      document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
      document.getElementById(tab).classList.remove('hidden');

      document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-green-700', 'text-green-700');
        btn.classList.add('border-transparent', 'text-gray-500');
      });

      button.classList.add('border-green-700', 'text-green-700');
      button.classList.remove('border-transparent', 'text-gray-500');
    });
  });
</script>
@endsection
