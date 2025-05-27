@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
    $currentFilter = request('filter');
    $currentCategoryId = $currentCategoryId ?? request('category_id');
@endphp

<div class="flex bg-white p-6">

  <!-- Sidebar kiri -->
  <div class="w-1/5 pr-4">
    {{-- Sidebar yang kamu sudah buat --}}
    <div class="border p-4 rounded-lg text-[#556B2F] font-semibold">
      <h2 class="text-lg mb-4">Sorted By</h2>
      <ul class="space-y-2">
        <li>
          <a href="{{ url('/menu') }}?filter=available" 
             class="hover:text-[#D4AF37] {{ $currentFilter == 'available' ? 'font-bold text-[#D4AF37]' : '' }}">
            Available Product
          </a>
        </li>
        <li>
          <a href="{{ url('/menu') }}?filter=recommended" 
             class="hover:text-[#D4AF37] {{ $currentFilter == 'recommended' ? 'font-bold text-[#D4AF37]' : '' }}">
            Recommended
          </a>
        </li>
      </ul>
    </div>

    <div class="border mt-6 p-4 rounded-lg text-[#556B2F] font-semibold">
      <h2 class="text-lg mb-4">Kategori</h2>
      <ul class="mt-4 space-y-2">
        @foreach($categories as $categoryItem)
          <li>
            <a href="{{ route('category.show', ['id' => $categoryItem->id]) }}"
               class="hover:text-[#D4AF37] {{ $currentCategoryId == $categoryItem->id ? 'font-bold text-[#D4AF37]' : '' }}">
              {{ $categoryItem->name }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  <!-- Konten utama kanan -->
  <div class="w-4/5">

    {{-- Produk Baru (New Products) --}}
    @if($newProducts->count())
      <section class="mb-8">
        <h2 class="text-2xl font-bold text-[#556B2F] mb-4">✨ Produk Baru</h2>
        <div class="grid grid-cols-4 gap-6 mb-8">
          @foreach($newProducts as $produk)
            <div class="border rounded-lg shadow-md overflow-hidden relative">

              {{-- Tag NEW --}}
              @if($produk->is_new_product)
              <div class="absolute top-2 left-2 bg-yellow-400 text-sm font-bold px-2 py-1 rounded shadow">
                  NEW
              </div>
              @endif

              @if($produk->image)
              <img src="{{ asset('storage/' . $produk->image) }}" alt="{{ $produk->title }}" class="w-full h-40 object-cover">
              @else
              <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
              @endif

              <div class="p-4 text-[#556B2F]">
                  <h3 class="font-bold">{{ $produk->title }}</h3>
                  <p class="text-[#D4AF37] font-semibold mb-1">
                      Rp {{ number_format($produk->price, 0, ',', '.') }}
                  </p>
                  <p class="text-sm text-gray-600 mb-2">
                      Stok: {{ $produk->stock }}
                  </p>

                  <button class="mt-3 w-full bg-[#D4AF37] text-white py-1 rounded"
                    @if($produk->stock == 0) disabled class="opacity-50 cursor-not-allowed" @endif>
                    {{ $produk->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                  </button>
              </div>
            </div>
          @endforeach
        </div>
      </section>
    @else
      <div class="text-center text-gray-400 italic mb-8">
        Belum ada produk baru yang ditampilkan.
      </div>
    @endif

    {{-- Bagian Kategori --}}
    @if(isset($category))
      <section class="mb-8 bg-[#f9f9f9] p-6 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-[#556B2F] mb-2">{{ $category->name }}</h2>
        <p class="text-gray-700 leading-relaxed">{{ $category->description ?? 'Deskripsi kategori belum tersedia.' }}</p>
      </section>
    @endif

    {{-- Produk utama --}}
    <div class="grid grid-cols-4 gap-6">
@forelse($products as $produk)
  <div class="border rounded-lg shadow-md overflow-hidden">
    @if($produk->image)
      <img src="{{ asset('' . $produk->image) }}" alt="{{ $produk->name }}" class="w-full h-40 object-cover">
    @else
      <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
    @endif

    <div class="p-4 text-[#556B2F]">
      <h3 class="font-bold">{{ $produk->name }}</h3>

      @if($produk->original_price)
        <p class="line-through text-sm text-gray-400">
          Rp. {{ number_format($produk->original_price, 0, ',', '.') }}
        </p>
      @endif

      <p class="text-[#D4AF37] font-semibold">
        Rp. {{ number_format($produk->discount_price ?? $produk->price, 0, ',', '.') }}
      </p>

      <div class="mt-2 flex items-center text-yellow-500">
        ★★★★★
      </div>

      <form action="{{ route('cart.add', $produk->id) }}" method="POST">
    @csrf
    <button
        type="submit"
        class="mt-3 w-full bg-[#D4AF37] text-white py-1 rounded {{ $produk->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
        {{ $produk->stock == 0 ? 'disabled' : '' }}
    >
        {{ $produk->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
    </button>
</form>


    </div>
  </div>
@empty
  <div class="col-span-4 text-center text-gray-400 italic">
    Belum ada produk yang ditampilkan untuk filter ini.
  </div>
@endforelse
    </div>

  </div>

</div>

@endsection
