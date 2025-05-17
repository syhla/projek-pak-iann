<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ config('app.name', 'Laravel') }}</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-[#FFF8F5] text-[#4B2E2E]">

  {{-- NAVBAR --}}
  <nav class="w-full bg-[#EAC6C1] px-6 py-3 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
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

      <ul class="flex space-x-6 font-semibold text-[#4B2E2E]">
        <li><a href="{{ route('about') }}" class="px-3 py-2 rounded-xl transition hover:bg-white hover:text-[#D4AF37] hover:shadow-md">About Us</a></li>
        <li><a href="#" class="px-3 py-2 rounded-xl transition hover:bg-white hover:text-[#D4AF37] hover:shadow-md">Menu</a></li>
        <li><a href="#" class="px-3 py-2 rounded-xl transition hover:bg-white hover:text-[#D4AF37] hover:shadow-md">Promo</a></li>
        <li><a href="#" class="px-3 py-2 rounded-xl transition hover:bg-white hover:text-[#D4AF37] hover:shadow-md">Berita</a></li>
        <li><a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl transition hover:bg-white hover:text-[#D4AF37] hover:shadow-md">Contact Us</a></li>
      </ul>

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
          <button onclick="toggleDropdown()" class="bg-white hover:bg-[#D4AF37] rounded-full p-2 text-2xl shadow transition">👤</button>
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

  {{-- KONTEN UTAMA --}}
  <main class="max-w-7xl mx-auto mt-10 -mt-8 px-6">
    @yield('content')
  </main>

  {{-- FOOTER --}}
  <footer class="mt-10 py-6 bg-[#EAC6C1] text-center text-[#4B2E2E] font-semibold">
    &copy; {{ date('Y') }} Nami Bakery. All rights reserved.
  </footer>

  <script>
    function toggleDropdown() {
      var menu = document.getElementById('dropdownMenu');
      menu.classList.toggle('hidden');
    }
  </script>

</body>
</html>
