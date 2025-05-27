@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
  <h1 class="text-2xl font-bold mb-6 text-gray-800">Daftar Komentar</h1>

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($komentar as $k)
          <tr class="hover:bg-pink-50 transition">
            <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">{{ $k->nama }}</td>
            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $k->pesan }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              @if($k->is_approved)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Approved
                </span>
              @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                  Menunggu
                </span>
              @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <a href="{{ route('admin.komentar.show', $k->id) }}" 
                 class="text-indigo-600 hover:text-indigo-900 font-semibold">
                Lihat
              </a>
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
@endsection
