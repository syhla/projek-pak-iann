@extends('layouts.app')
@section('content')

{{-- Hero Section --}}
<section class="text-center py-16 px-6 bg-[#F3F8DD]">
  <h1 class="text-6xl md:text-5xl font-logo text-[#D4AF37] mb-6">Hello! Kami Nami Bakery</h1>
  <p class="max-w-3xl mx-auto text-lg leading-relaxed text-[#4B2E2E]">
“Nami Bakery adalah toko roti artisan lokal yang berarti ‘SETIAP IRISAN BERARTI’. Kami pertama kali membuka toko pada tahun 2020, dan kini dengan bangga melayani pelanggan di berbagai kota di seluruh Indonesia. Nami Bakery berdedikasi untuk menghadirkan makanan panggang segar dan lezat setiap hari, hanya menggunakan bahan-bahan alami premium. Setiap kreasi kami dibuat dengan cinta, gairah, dan sentuhan kehangatan rumah.”  </p>
</section>

{{-- Our Story Section --}}
<section class="bg-[#556B2F] text-white text-center py-16 px-6">
  <h2 class="text-4xl font-logo text-[#D4AF37] mb-3">Cerita Kita</h2>
  <h3 class="text-xl font-semibold mx-auto">
TOKO ROTI RUMAHAN DENGAN ANEKA PRODUK PANGANGGAN SEGAR SETIAP HARI.  </h3>
</section>

{{-- Global Vision Split Layout (Isi dari global section dipindah ke sini) --}}
<section class="py-16 px-6 bg-white max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
  <div class="px-4 md:px-6">
    <h3 class="text-3xl font-semibold text-[#D4AF37] mb-4">Nami Bakery</h3>
    <p class="text-[#4B2E2E] leading-relaxed">
Didirikan pada tahun 2020, Nami Bakery berkembang dari toko keluarga kecil menjadi merek yang dipercaya pelanggan di berbagai kota di Indonesia. Kami berkomitmen memperluas jangkauan sambil tetap setia pada nilai inti: menggunakan bahan premium dan memanggang dengan cinta.    </p>
    <h4 class="text-[#556B2F] italic text-xl mt-4">Kami adalah bakery lokal Indonesia dengan visi global</h4>

  </div>

  <div class="flex justify-center">
    <img src="{{ asset('EMPINK KUE.jpg') }}" alt="Ingredients and Bread"
         class="w-full max-w-md rounded-lg shadow-lg">
  </div>
</section>

{{-- Healthy Baked Goods Section --}}
<section class="py-16 px-6 bg-white grid md:grid-cols-2 gap-8 items-center max-w-7xl mx-auto">
  <img src="{{ asset('ROTI.jpg') }}" alt="Kneading Dough"
       class="w-full max-w-md mx-auto shadow-md rounded-md">
  
  <div>
    <h3 class="text-2xl text-[#D4AF37] font-semibold italic mb-2">Makanan Panggang Yang Sehat</h3>
    <h4 class="text-lg text-[#556B2F] italic mb-4">dengan bahan-bahan paling murni dan terbaik
</h4>
    <p class="text-[#4B2E2E] leading-relaxed">
Kami menawarkan berbagai macam makanan panggang buatan tangan—mulai dari roti, kue, dan biskuit hingga makanan musiman spesial.
Setiap produk disiapkan dengan cermat hanya menggunakan bahan-bahan terbaik. Di Nami Bakery, kami percaya bahwa
setiap potong itu penting dan kesegaran adalah kunci kualitas. Itulah sebabnya produk kami dipanggang segar setiap hari
hanya untuk Anda.    </p>
  </div>
</section>

{{-- Store Pertama (Teks di atas, gambar besar di bawah) --}}
<section class="py-16 px-6 bg-[#F3F8DD] text-center">
  <h3 class="text-6xl md:text-5xl font-logo text-[#D4AF37] mb-4">Our First Store</h3>
  <p class="max-w-3xl mx-auto text-[#4B2E2E] text-lg leading-relaxed mb-10">
Pada tahun 2020, kami membuka toko pertama kami — sebuah toko roti yang nyaman, dipenuhi aroma roti segar dan kehangatan rumah.
Di sinilah perjalanan Nami Bakery dimulai. Lebih dari sekadar toko, ini adalah wujud kenangan akan asal usul, gairah, dan kecintaan kami terhadap seni membuat kue.  </p>

  <div class="w-full max-w-5xl mx-auto">
    <img src="{{ asset('EMPINK KUE.jpg') }}" alt="Our First Store"
         class="w-full rounded-lg shadow-xl">
  </div>
</section>

@endsection
