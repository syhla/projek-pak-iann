@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-extrabold mb-6 text-green-700 text-center">🍰 Custom Kue Ulang Tahun</h1>

{{-- Notifikasi sukses dan QR code --}}
@if(session('success'))
  <div class="bg-green-100 border border-green-300 text-green-800 p-4 rounded shadow mb-6">
    Pesanan sudah dikirim, tunggu admin menyetujui ya. 
  </div>
@endif

    {{-- Form custom cake --}}
    <form action="{{ route('customer.custom.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 bg-white p-8 rounded-2xl shadow-xl border border-green-100"
        onsubmit="return confirm('Yakin ingin mengirim pesanan ini?')">
        @csrf

        {{-- Nama --}}
        <div>
            <label for="nama" class="block font-semibold text-gray-700 mb-1">👤 Nama Pemesan</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Masukkan nama kamu">
        </div>

        {{-- No WA --}}
        <div>
            <label for="no_wa" class="block font-semibold text-gray-700 mb-1">📱 No. WhatsApp</label>
            <input type="text" id="no_wa" name="no_wa" value="{{ old('no_wa') }}" required pattern="[0-9]+"
                title="Hanya angka yang diperbolehkan"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Contoh: 08xxxxxxxxxx">
        </div>

        {{-- Desain --}}
        <div>
            <label for="desain" class="block font-semibold text-gray-700 mb-1">🎨 Desain Kue yang Diinginkan</label>
            <textarea id="desain" name="desain" rows="4" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Contoh: tema Hello Kitty, warna pink, 2 tingkat, dll.">{{ old('desain') }}</textarea>
        </div>

        {{-- Gambar Referensi --}}
        <div>
            <label for="gambar_referensi" class="block font-semibold text-gray-700 mb-1">📎 Upload Gambar Referensi (opsional)</label>
            <input type="file" id="gambar_referensi" name="gambar_referensi" accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        {{-- Submit --}}
        <div class="text-center">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition duration-300">
                Kirim Pesanan 🍰
            </button>
        </div>
    </form>
</div>
@endsection
