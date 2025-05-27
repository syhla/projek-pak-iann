<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Bootstrap Bundle JS (sudah termasuk Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    .font-logo {
      font-family: 'Great Vibes', cursive;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #FDFBF6;
      color: #4B2E2E;
    }
    html, body {
      min-height: 100%;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="w-full bg-[#556B2F] px-6 py-3 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

      @php
        $user = Auth::user();
        $redirectLink = '/';
      @endphp

      <!-- Logo dan nama -->
      <a href="{{ $redirectLink }}" class="flex items-center space-x-3">
        <img src="{{ asset('logo ijo.png') }}" alt="Logo" class="h-16 w-auto object-contain rounded-full" />
        <span class="text-4xl font-logo text-white">Nami Bakery</span>
      </a>

      <!-- Menu navigasi -->
      <ul class="flex space-x-6 font-semibold text-[#FFF8F5]">
        <li><a href="{{ route('about') }}" class="px-3 py-2 rounded-xl transition hover:bg-[#D4AF37] hover:text-[#FFF8F5] hover:shadow-md">About Us</a></li>
        <li><a href="{{ route('menu') }}" class="px-3 py-2 rounded-xl transition hover:bg-[#D4AF37] hover:text-[#FFF8F5] hover:shadow-md">Menu</a></li>
        <li><a href="{{ route('promo') }}" class="px-3 py-2 rounded-xl transition hover:bg-[#D4AF37] hover:text-[#FFF8F5] hover:shadow-md">Promo</a></li>
        <li><a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl transition hover:bg-[#D4AF37] hover:text-[#FFF8F5] hover:shadow-md">Contact Us</a></li>
      </ul>

      <!-- Bagian kanan (Cart + Dropdown akun) -->
      <div class="flex items-center space-x-4 text-white">

        @auth
          @if ($user->hasRole('customer'))
            <button
              id="cartButton"
              class="relative bg-[#FFF8F5] rounded-full p-2 text-2xl shadow hover:bg-[#D4AF37] transition"
              title="Cart"
              type="button"
              aria-label="Open shopping cart"
              aria-haspopup="true"
              aria-expanded="false"
            >
              🛒
              @if (!empty($cartCount) && $cartCount > 0)
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                  {{ $cartCount }}
                </span>
              @endif
            </button>
          @endif
        @else
          <a href="{{ route('login') }}"
             class="bg-[#FFF8F5] rounded-full p-2 text-2xl shadow hover:bg-[#D4AF37] transition"
             title="Login to see your cart"
             aria-label="Login to see your cart"
          >
            🛒
          </a>
        @endauth

        <!-- Dropdown akun -->
        <div class="relative">
          <button onclick="toggleDropdown()" class="bg-[#FFF8F5] hover:bg-[#D4AF37] rounded-full p-2 text-2xl shadow transition" type="button" aria-haspopup="true" aria-expanded="false" aria-label="Account menu">👤</button>
          <div id="dropdownMenu" class="absolute right-0 mt-2 w-44 bg-[#FFF8F5] rounded-md shadow-lg hidden z-50 text-sm text-[#4B2E2E]">
            @guest
              <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-[#D4AF37] hover:text-white font-semibold">Login</a>
              @if (Route::has('register'))
                <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-[#D4AF37] hover:text-white font-semibold">Register</a>
              @endif
            @else
              @if($user->hasRole('admin'))
                <a href="{{ url('/admin/dashboard') }}" class="block px-4 py-2 hover:bg-[#D4AF37] hover:text-white font-semibold">Dashboard Admin</a>
              @elseif($user->hasRole('customer'))
                <a href="{{ url('/customer/dashboard') }}" class="block px-4 py-2 hover:bg-[#D4AF37] hover:text-white font-semibold">Dashboard Customer</a>
              @endif
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-[#D4AF37] hover:text-white font-semibold">Logout</button>
              </form>
            @endguest
          </div>
        </div>
      </div>

    </div>
  </nav>

  <!-- Overlay -->
  <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeCart()" aria-hidden="true"></div>

<div id="cartSidebar" class="fixed top-0 right-0 h-full w-80 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out z-50 p-6 flex flex-col" role="dialog" aria-modal="true" aria-labelledby="cartTitle">

  <h2 id="cartTitle" class="text-2xl font-bold mb-6 border-b pb-4">🛒 Keranjang Belanja</h2>

  @php
    $cart = session('cart', []);
  @endphp

  @if (empty($cart))
    <p class="text-center text-gray-400 italic mt-10">Keranjang kamu kosong.</p>
  @else

<!-- Bagian subtotal harga per item ditambahkan di tiap item keranjang -->
<ul class="space-y-4 overflow-y-auto flex-grow">
  @foreach($cart as $productId => $item)
    <li class="flex items-start justify-between border rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow bg-gray-50">

      <input type="checkbox" name="selected_products[]" value="{{ $productId }}" form="checkoutForm" class="mr-2 mt-1" checked>

      <div class="flex flex-col max-w-xs flex-grow">
        <p class="font-semibold text-lg text-gray-800 truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</p>
        <p class="text-sm text-gray-600">
          Harga: 
          <span class="font-medium price-per-item" data-price="{{ $item['price'] }}">
            Rp {{ number_format($item['price'], 0, ',', '.') }}
          </span>
        </p>
        <p class="text-sm text-gray-800 font-semibold mt-1">
          Subtotal: <span class="subtotal">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
        </p>

        <div class="flex items-center space-x-2 mt-1">
          <button type="button" class="decrease-qty bg-gray-300 px-2 rounded text-sm" data-product-id="{{ $productId }}">-</button>
          <input 
            type="number" 
            name="quantities[{{ $productId }}]" 
            value="{{ $item['quantity'] }}" 
            min="1" 
            form="checkoutForm" 
            class="w-12 text-center border rounded text-sm"
            data-product-id="{{ $productId }}"
          >
          <button type="button" class="increase-qty bg-gray-300 px-2 rounded text-sm" data-product-id="{{ $productId }}">+</button>
        </div>
      </div>

      <!-- Form hapus berdiri sendiri -->
      <form action="{{ route('cart.remove', $productId) }}" method="POST" class="ml-3 flex-shrink-0">
        @csrf
        @method('DELETE')
        <button type="submit"
         class="text-red-600 hover:text-red-800 font-semibold focus:outline-none focus:ring-2 focus:ring-red-400 rounded px-3 py-1.5 text-xl"
         aria-label="Hapus {{ $item['name'] }} dari keranjang">
         &times;
        </button>
      </form>
    </li>
  @endforeach
</ul>

    <!-- Form checkout terpisah -->
    <form id="checkoutForm" action="{{ route('checkout.page') }}" method="POST" class="mt-6 flex justify-between items-center border-t pt-4 gap-6">
      @csrf
      <button type="button" id="closeCartModal" onclick="closeCart()" class="px-6 py-2 bg-gray-300 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">Tutup</button>
      <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Checkout</button>
    </form>

  @endif
</div>

  <!-- KONTEN UTAMA -->
  <main class="bg-[#F3F8DD] min-h-screen">
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="bg-[#556B2F] text-[#ffffff] py-10 border-t border-[#D4AF37]">
    <div class="max-w-7xl mx-auto px-10 grid md:grid-cols-4 gap-8">

      <!-- Info Umum -->
      <div>
        <h3 class="font-bold mb-4">INFORMASI UMUM</h3>
        <ul class="space-y-2">
          <li><a href="#" class="inline-block transition duration-300 transform hover:translate-x-1 hover:text-[#D4AF37]">Tentang Kami</a></li>
          <li><a href="#" class="inline-block transition duration-300 transform hover:translate-x-1 hover:text-[#D4AF37]">Contact Kami</a></li>
          <li><a href="#" class="inline-block transition duration-300 transform hover:translate-x-1 hover:text-[#D4AF37]">Berita</a></li>
          <li><a href="#" class="inline-block transition duration-300 transform hover:translate-x-1 hover:text-[#D4AF37]">Menu</a></li>
        </ul>
      </div>

      <!-- Kontak -->
      <div>
        <h3 class="font-bold mb-4">KONTAK KAMI</h3>
        <p>📱 +62 812 1314 1500</p>
        <p>📧 namibakery@gmail.com</p>
      </div>

          <!-- Sosmed -->
          <div>
            <h3 class="font-bold mb-4">TEMUKAN KAMI DI</h3>
            <div class="flex gap-3 items-center">
              <a href="#" aria-label="Instagram" class="hover:text-[#D4AF37] transition">
                <img src="https://img.icons8.com/ios-filled/24/ffffff/instagram-new.png" alt="Instagram" />
              </a>
              <a href="#" aria-label="Facebook" class="hover:text-[#D4AF37] transition">
                <img src="https://img.icons8.com/ios-filled/24/ffffff/facebook-new.png" alt="Facebook" />
              </a>
              <a href="#" aria-label="Twitter" class="hover:text-[#D4AF37] transition">
                <img src="https://img.icons8.com/ios-filled/24/ffffff/twitter.png" alt="Twitter" />
              </a>
            </div>
          </div>

          <!-- Logo + Hak Cipta -->
          <div class="flex flex-col items-center justify-center">
            <img src="{{ asset('logo ijo.png') }}" alt="Logo Nami Bakery" class="h-16 mb-2" />
            <p class="text-sm">© 2023 Nami Bakery. All rights reserved.</p>
          </div>
        </div>
      </footer>

<script>
  // Toggle dropdown menu akun
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    if (!dropdown) return;
    dropdown.classList.toggle('hidden');
  }

  // Format angka jadi Rupiah (e.g. 15000 => Rp 15.000)
  function formatRupiah(number) {
    return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  document.addEventListener('DOMContentLoaded', () => {
    const cartButton = document.getElementById('cartButton');
    const cartSidebar = document.getElementById('cartSidebar');
    const cartModal = document.getElementById('cartModal');

    // Fungsi buka sidebar cart
    function openCart() {
      cartSidebar.classList.remove('translate-x-full');
      cartModal.classList.remove('hidden');
      if (cartButton) cartButton.setAttribute('aria-expanded', 'true');
    }

    // Fungsi tutup sidebar cart
    function closeCart() {
      cartSidebar.classList.add('translate-x-full');
      cartModal.classList.add('hidden');
      if (cartButton) cartButton.setAttribute('aria-expanded', 'false');
    }

    if (cartButton && cartSidebar && cartModal) {
      cartButton.addEventListener('click', openCart);
      cartModal.addEventListener('click', closeCart);
      const closeBtn = document.getElementById('closeCartModal');
      if (closeBtn) closeBtn.addEventListener('click', closeCart);
    }

    // Update subtotal harga per produk
    function updateSubtotal(productId) {
      const container = document.querySelector(`button[data-product-id='${productId}']`).closest('li');
      const qtyInput = container.querySelector(`input[name='quantities[${productId}]']`);
      const qty = parseInt(qtyInput.value) || 1;
      const pricePerItemElem = container.querySelector('.price-per-item');
      const price = parseInt(pricePerItemElem.dataset.price);
      const subtotalElem = container.querySelector('.subtotal');
      subtotalElem.textContent = formatRupiah(price * qty);
    }

    // Event tombol tambah quantity
    document.querySelectorAll('.increase-qty').forEach(button => {
      button.addEventListener('click', () => {
        const productId = button.dataset.productId;
        const qtyInput = document.querySelector(`input[name='quantities[${productId}]']`);
        let qty = parseInt(qtyInput.value) || 1;
        qty++;
        qtyInput.value = qty;
        updateSubtotal(productId);
      });
    });

    // Event tombol kurang quantity
    document.querySelectorAll('.decrease-qty').forEach(button => {
      button.addEventListener('click', () => {
        const productId = button.dataset.productId;
        const qtyInput = document.querySelector(`input[name='quantities[${productId}]']`);
        let qty = parseInt(qtyInput.value) || 1;
        if (qty > 1) {
          qty--;
          qtyInput.value = qty;
          updateSubtotal(productId);
        }
      });
    });

    // Event input manual quantity
    document.querySelectorAll("input[name^='quantities']").forEach(input => {
      input.addEventListener('change', () => {
        const productId = input.dataset.productId;
        let qty = parseInt(input.value);
        if (isNaN(qty) || qty < 1) {
          qty = 1;
          input.value = qty;
        }
        updateSubtotal(productId);
      });
    });
  });
</script>

    </body>
</html>
