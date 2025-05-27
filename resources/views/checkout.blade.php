@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-3xl mx-auto p-8">
  <h1 class="text-4xl font-extrabold mb-8 text-green-700 border-b pb-4">🛒 Checkout</h1>

  @if(session('success'))
    <div class="mb-6 bg-green-100 text-green-700 p-4 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if(count($checkoutItems) > 0)
    @php $totalPrice = 0; @endphp

    <ul class="mb-8 space-y-4">
      @foreach($checkoutItems as $item)
        @php
          $subtotal = $item['price'] * $item['quantity'];
          $totalPrice += $subtotal;
        @endphp
        <li class="flex justify-between items-center border rounded-lg p-4 bg-green-50 shadow-sm">
          <div>
            <h2 class="font-semibold text-lg text-gray-800">{{ $item['name'] }}</h2>
            <p class="text-sm text-gray-600">Qty: {{ $item['quantity'] }}</p>
          </div>
          <div class="text-green-700 font-semibold text-lg">
            Rp {{ number_format($subtotal, 0, ',', '.') }}
          </div>
        </li>
      @endforeach
    </ul>

    <div class="text-right mb-6 text-xl font-bold text-green-800">
      Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}
    </div>

    <form action="{{ route('checkout.confirm') }}" method="POST" class="space-y-6 bg-white p-6 rounded shadow-md">
      @csrf

      <input type="hidden" name="items" value='@json($checkoutItems)'>

      <div>
        <label for="no_hp" class="block font-semibold text-gray-700">No. HP</label>
        <input type="text" name="no_hp" id="no_hp" required class="w-full rounded border border-gray-300 p-2"
               placeholder="081234567890" value="{{ old('no_hp') }}">
        @error('no_hp')<div class="text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label for="alamat" class="block font-semibold text-gray-700">Alamat Pengiriman</label>
        <textarea name="alamat" id="alamat" rows="3" required class="w-full rounded border border-gray-300 p-2"
                  placeholder="Alamat lengkap pengiriman">{{ old('alamat') }}</textarea>
        @error('alamat')<div class="text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label for="payment_method" class="block font-semibold text-gray-700">Metode Pembayaran</label>
        <select name="payment_method" id="payment_method" required class="w-full rounded border border-gray-300 p-2">
          <option value="" disabled selected>Pilih metode pembayaran</option>
          <option value="transfer">Transfer Bank</option>
          <option value="cod">Cash on Delivery</option>
          <option value="ewallet">E-Wallet</option>
        </select>
        @error('payment_method')<div class="text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label for="shipping_method" class="block font-semibold text-gray-700">Metode Pengiriman</label>
        <select name="shipping_method" id="shipping_method" required class="w-full rounded border border-gray-300 p-2">
          <option value="" disabled selected>Pilih metode pengiriman</option>
          <option value="jne">JNE</option>
          <option value="tiki">TIKI</option>
          <option value="grab">GrabExpress</option>
          <option value="gojek">Gojek</option>
        </select>
        @error('shipping_method')<div class="text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
        Konfirmasi Pesanan
      </button>
    </form>
  @else
    <p class="text-gray-600">Keranjang kamu kosong.</p>
  @endif
</div>
@endsection
