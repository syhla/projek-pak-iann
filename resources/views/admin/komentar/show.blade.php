@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">

  <h2 class="text-2xl font-semibold mb-4 text-gray-800 border-b pb-2">
    Komentar dari: <span class="text-pink-600">{{ $komentar->nama }}</span>
  </h2>

  <p class="mb-6 text-gray-700 text-lg leading-relaxed">
    {{ $komentar->pesan }}
  </p>

  @if(!$komentar->is_approved)
    <div class="flex space-x-4">
      <form method="POST" action="{{ route('admin.komentar.approve', $komentar->id) }}">
          @csrf
          <button type="submit" 
                  class="bg-green-500 hover:bg-green-600 transition text-white px-6 py-2 rounded shadow-md">
            Setujui
          </button>
      </form>

      <form method="POST" action="{{ route('admin.komentar.reject', $komentar->id) }}">
          @csrf
          <button type="submit" 
                  class="bg-red-500 hover:bg-red-600 transition text-white px-6 py-2 rounded shadow-md"
                  onclick="return confirm('Yakin ingin menolak dan menghapus komentar ini?');">
            Tolak
          </button>
      </form>
    </div>
  @else
    <p class="mt-4 text-green-600 font-semibold text-lg">Komentar ini sudah di-approve dan tampil di halaman utama.</p>
  @endif

</div>
@endsection
