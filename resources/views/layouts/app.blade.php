<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Nami Bakery') }}</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-pZ5XsUipG7SvhRjw6bp7wQ2RymHPXq1jZJSarZTcW+t3X2E4IQqqWqv+7K7kaN0B8zH5uNIs7VJKUhwRf4usw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Global Styles -->
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
    }
    .font-logo {
      font-family: 'Dancing Script', cursive;
    }
  </style>
</head>
<body class="bg-[#FFF5EC] text-[#4B2E2E] min-h-screen flex flex-col">

  {{-- Navbar --}}
  <nav class="w-full bg-[#EAC6C1] px-6 py-3 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      <!-- Logo -->
@php
  $user = Auth::user();
  $redirectLink = '/'; // default ke welcome

  if ($user && $user->hasRole('admin')) {
      $redirectLink = '/admin/dashboard';
  }
@endphp

<a href="{{ $redirectLink }}" class="flex items-center space-x-3">
  <img src="{{ asset('LOGOW.jpg') }}" alt="Logo" class="h-16 w-auto object-contain rounded-full" />
  <span class="text-3xl font-bold text-[#4B2E2E] font-logo">Nami Bakery</span>
</a>
      <!-- Menu -->
      <ul class="flex space-x-6 font-semibold text-[#4B2E2E]">
        <li><a href="{{ route('about') }}" class="relative group px-3 py-2 transition rounded-xl hover:bg-white hover:text-[#D4AF37] hover:shadow-md">About Us</a></li>
        <li><a href="#" class="relative group px-3 py-2 transition rounded-xl hover:bg-white hover:text-[#D4AF37] hover:shadow-md">Menu</a></li>
        <li><a href="#" class="relative group px-3 py-2 transition rounded-xl hover:bg-white hover:text-[#D4AF37] hover:shadow-md">Promo</a></li>
        <li><a href="#" class="relative group px-3 py-2 transition rounded-xl hover:bg-white hover:text-[#D4AF37] hover:shadow-md">Berita</a></li>
<li>
  <a href="{{ route('contact') }}" class="relative group px-3 py-2 transition rounded-xl hover:bg-white hover:text-[#D4AF37] hover:shadow-md">
    Contact Us
  </a>
</li>
      </ul>

      <!-- Keranjang & Akun -->
      <div class="flex items-center space-x-4 text-[#4B2E2E]">
<!-- Keranjang & Akun -->
<div class="flex items-center space-x-4 text-[#4B2E2E]">
  @guest
    <button class="bg-white rounded-full p-2 text-2xl shadow hover:bg-[#D4AF37] transition">🛒</button>
  @endguest

  @auth
    @if (Auth::user()->hasRole('customer'))
      <button class="bg-white rounded-full p-2 text-2xl shadow hover:bg-[#D4AF37] transition">🛒</button>
    @endif
  @endauth
        <div class="relative">
          <button onclick="toggleDropdown()" class="bg-white hover:bg-[#D4AF37] rounded-full p-2 text-2xl shadow hover:text-[#D4AF37] transition">👤</button>
          <div id="dropdownMenu" class="absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg hidden z-50 text-sm">
            @guest
              <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-[#EAC6C1] font-semibold">Login</a>
              @if (Route::has('register'))
                <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-[#EAC6C1] font-semibold">Register</a>
              @endif
            @else
              @if(Auth::user()->hasRole('admin'))
                <a href="{{ url('/admin/dashboard') }}" class="block px-4 py-2 hover:bg-[#EAC6C1] font-semibold">Dashboard Admin</a>
              @elseif(Auth::user()->hasRole('customer'))
                <a href="{{ url('/customer/dashboard') }}" class="block px-4 py-2 hover:bg-[#EAC6C1] font-semibold">Dashboard Customer</a>
              @endif
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-[#EAC6C1] font-semibold">Logout</button>
              </form>
            @endguest
          </div>
        </div>
      </div>
    </div>
  </nav>

  {{-- Main Content --}}
  <main class="flex-grow ">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="bg-[#EAC6C1] py-8 text-sm mt-auto">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
      <div>
        <h3 class="text-lg font-bold mb-3">Nami Bakery</h3>
        <p>Toko roti lokal dengan cita rasa premium.</p>
      </div>
      <div>
        <h3 class="text-lg font-bold mb-3">Navigasi</h3>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-[#D4AF37] transition">Tentang Kami</a></li>
          <li><a href="#" class="hover:text-[#D4AF37] transition">Menu</a></li>
          <li><a href="#" class="hover:text-[#D4AF37] transition">Promo</a></li>
          <li><a href="#" class="hover:text-[#D4AF37] transition">Berita</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-lg font-bold mb-3">Hubungi Kami</h3>
        <p>Email: info@namibakery.com</p>
        <p>Telepon: +62 812 3456 7890</p>
        <div class="flex space-x-4 mt-3 text-xl">
          <a href="#" class="hover:text-[#D4AF37] transition">📘</a>
          <a href="#" class="hover:text-[#D4AF37] transition">📸</a>
          <a href="#" class="hover:text-[#D4AF37] transition">🐦</a>
        </div>
      </div>
    </div>
    <div class="text-center mt-6 text-xs">
      &copy; {{ date('Y') }} Nami Bakery. All rights reserved.
    </div>
  </footer>

  {{-- Script dropdown --}}
  <script>
    function toggleDropdown() {
      const menu = document.getElementById('dropdownMenu');
      menu.classList.toggle('hidden');
    }

    window.addEventListener('click', function(e) {
      const button = e.target.closest('button');
      const dropdown = document.getElementById('dropdownMenu');
      if (!e.target.closest('#dropdownMenu') && !button) {
        dropdown.classList.add('hidden');
      }
    });
  </script>

</body>
</html>
