@extends('layouts.app')

@section('content')
<div class="px-4 md:px-16 py-8 space-y-20"> {{-- spacing antar section pakai space-y biar konsisten --}}

  {{-- === ADMIN: Upload Slide === --}}
  @auth
    @if(auth()->user()->hasRole('admin'))
      <div>
        <form action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
          @csrf
          <div class="flex items-end gap-4">
            <div>
              <label for="image" class="block text-lg font-bold mb-1">Upload Gambar</label>
              <input type="file" name="image" class="p-2 border text-sm w-72">
              @error('image')
                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
              @enderror
            </div>
            <div class="pb-1">
              <button type="submit" class="bg-[#D4AF37] hover:bg-[#b3932c] text-white py-2 px-4 rounded-md">
                Upload
              </button>
            </div>
          </div>
        </form>
      </div>
    @endif
  @endauth

  {{-- === CAROUSEL SLIDER === --}}
  <div class="relative overflow-hidden rounded-lg shadow-md h-[450px]">
    <div id="slider" class="relative w-full h-full">
      @foreach ($slides as $slide)
        <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}">
          <img src="{{ Storage::url($slide->image_path) }}" class="w-full h-full object-cover rounded-md" alt="Slide Image">
          @auth
            @if(auth()->user()->hasRole('admin'))
              <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" class="absolute top-4 right-4 z-10">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-400 hover:bg-red-500 text-white p-2 rounded-full">Delete</button>
              </form>
            @endif
          @endauth
        </div>
      @endforeach
    </div>
  </div>

  {{-- === BEST SELLER MENU === --}}
  <section class="max-w-6xl mx-auto px-6 space-y-10">
    {{-- Judul --}}
    <div class="text-center">
      <p class="text-[#0f6c3c] italic text-lg mb-0">Our</p>
      <div class="flex justify-center items-center mt-1">
        <div class="h-1 w-16 bg-gray-400"></div>
        <h1 class="mx-3 font-bold text-[#D4AF37]" style="font-size: 2rem; letter-spacing: 1px;">
          BEST SELLER MENU
        </h1>
        <div class="h-1 w-16 bg-gray-400"></div>
      </div>
    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
      @foreach ($products as $product)
        <div class="border rounded-lg p-4 shadow hover:shadow-lg transition flex flex-col">
          @if ($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                 class="w-full h-40 object-cover rounded-md mb-3">
          @else
            <div class="w-full h-40 bg-gray-200 rounded-md mb-3 flex items-center justify-center text-gray-400">
              No Image
            </div>
          @endif

          <h3 class="text-lg font-semibold mb-1">{{ $product->name }}</h3>

          <p class="text-gray-600 mb-2 flex-grow overflow-hidden text-ellipsis" style="max-height: 3.5rem;">
            {{ $product->description }}
          </p>

          <p class="text-[#D4AF37] font-semibold text-lg">
            Rp {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}
          </p>

          <button class="mt-3 w-full bg-[#D4AF37] text-white py-2 rounded hover:bg-yellow-500 transition">
            Add to Cart
          </button>
        </div>
      @endforeach
    </div>
  </section>

</div> {{-- penutup div utama --}}

{{-- NEW MENU --}}
<section class="max-w-6xl mx-auto px-6 space-y-10">
  {{-- Judul --}}
  <div class="text-center">
    <p class="text-[#0f6c3c] italic text-lg mb-0">Our</p>
    <div class="flex justify-center items-center mt-1">
      <div class="h-1 w-16 bg-gray-400"></div>
      <h2 class="mx-3 font-bold text-[#D4AF37] tracking-wide text-2xl">NEW MENU</h2>
      <div class="h-1 w-16 bg-gray-400"></div>
    </div>
  </div>

  {{-- Grid Gambar --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
    @foreach ($newProducts as $index => $product)
      @if ($loop->first)
        {{-- Gambar utama --}}
        <div class="col-span-2 row-span-2">
          <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
               class="w-full h-80 object-cover rounded-xl shadow">
        </div>
      @else
        {{-- Gambar lain --}}
        <div>
          <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
               class="w-full h-full object-cover rounded-xl shadow">
        </div>
      @endif
    @endforeach
  </div>
</section>

{{-- CUSTOM CREATION --}}
<section class="max-w-6xl mx-auto px-6 mt-14 space-y-10">
  {{-- Judul --}}
  <div class="text-center">
    <div class="flex justify-center items-center mt-1">
      <div style="height: 4px; width: 60px; background-color: #807e7e;"></div>
      <h2 class="mx-3 font-bold text-[#D4AF37]" style="font-size: 2rem; letter-spacing: 1px;">
        CUSTOM CREATION
      </h2>
      <div style="height: 4px; width: 60px; background-color: #807e7e;"></div>
    </div>
  </div>

  {{-- Deskripsi + CTA --}}
  <div class="max-w-4xl mx-auto text-center text-[#556B2F] space-y-6">
    <p class="text-base md:text-lg leading-relaxed">
      Bagi Anda yang menginginkan kue custom dengan desain sesuai keinginan,
      Nami Bakery menyediakan dekorasi kue profesional menggunakan <strong>Fondant</strong>
      atau <strong>Buttercream</strong> yang dapat disesuaikan dengan preferensi Anda.
    </p>

@auth
    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.custom.index') }}"
           class="inline-block bg-[#556B2F] text-white px-6 py-3 rounded-md shadow hover:bg-[#445422] transition">
            PESAN SEKARANG JUGA
        </a>
    @elseif(auth()->user()->hasRole('customer'))
        <a href="{{ route('customer.custom.index') }}"
           class="inline-block bg-[#556B2F] text-white px-6 py-3 rounded-md shadow hover:bg-[#445422] transition">
            PESAN SEKARANG JUGA
        </a>
    @else
        {{-- Kalau ada role lain yang login tapi bukan admin/customer --}}
        <a href="{{ route('login') }}"
           class="inline-block bg-[#556B2F] text-white px-6 py-3 rounded-md shadow hover:bg-[#445422] transition">
            LOGIN TO ORDER
        </a>
    @endif
@else
    <a href="{{ route('login') }}"
       class="inline-block bg-[#556B2F] text-white px-6 py-3 rounded-md shadow hover:bg-[#445422] transition">
        LOGIN TO ORDER
    </a>
@endauth
</div>

{{-- CUSTOMER REVIEW --}}
<section class="max-w-6xl mx-auto px-6 space-y-10 py-10">
  {{-- Judul --}}
  <div class="text-center">
    <p class="text-[#0f6c3c] italic text-lg mb-0">Our</p>
    <div class="flex justify-center items-center mt-1">
      <div class="h-1 w-16 bg-gray-400"></div>
      <h2 class="mx-3 font-bold text-[#D4AF37]" style="font-size: 2rem; letter-spacing: 1px;">
        CUSTOMER REVIEW
      </h2>
      <div class="h-1 w-16 bg-gray-400"></div>
    </div>
  </div>

  {{-- Review Grid --}}
  <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
    @foreach($comments as $komentar)
      <div class="bg-gradient-to-br from-[#f0edb6] to-[#556B2F] text-[#556B2F] rounded-2xl p-6 shadow-xl relative">
        <div class="text-2xl font-light mb-4 leading-relaxed">
          “{{ $komentar->pesan }}”
        </div>

        <div class="flex items-center mt-6">
          <img 
            src="{{ $komentar->user->avatar_url ?? asset('akun.png') }}" 
            alt="{{ $komentar->user->nama ?? 'Pengguna' }}" 
            class="w-12 h-12 rounded-full"
            style="background-color: #f6f19d;" 
          />
          <div>
            <p class="font-semibold ml-2">{{ $komentar->user->nama ?? 'Pengguna' }}</p>
          </div>
        </div>

        <div class="absolute right-0 bottom-0 opacity-20">
          <svg width="120" height="120" fill="none"><circle cx="60" cy="60" r="60" fill="white"/></svg>
        </div>
      </div>
    @endforeach
  </div>
</section>

{{-- SCRIPT SLIDER --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    let currentSlide = 0;
    const slides = document.querySelectorAll('#slider .carousel-slide');
    const totalSlides = slides.length;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.toggle('opacity-100', i === index);
        slide.classList.toggle('opacity-0', i !== index);
      });
    }

    function nextSlide() {
      currentSlide = (currentSlide + 1) % totalSlides;
      showSlide(currentSlide);
    }

    showSlide(currentSlide);
    setInterval(nextSlide, 4000);
  });
</script>

{{-- FONT & STYLING --}}
<style>
  @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Inter:wght@400;600&display=swap');
  .font-logo {
    font-family: 'Great Vibes', cursive;
  }
  .scrollbar-hide::-webkit-scrollbar { display: none; }
  .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

  .card-promo {
  transition: transform 0.3s ease;
}
.card-promo:hover {
  transform: scale(1.05);
}
</style>
@endsection