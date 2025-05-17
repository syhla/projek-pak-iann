@extends('layouts.app')

@section('content')
<div class="px-6 md:px-16 py-20">

  {{-- Bagian 1: Upload Slide Gambar --}}
  @auth
    @if(auth()->user()->hasRole('admin'))
      <div class="mb-8">
        <form action="{{ route('slides.store') }}" method="POST" enctype="multipart/form-data">
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
              <button type="submit" class="bg-pink-400 hover:bg-pink-500 text-white py-2 px-4 rounded-md">Upload</button>
            </div>
          </div>
        </form>
      </div>
    @endif
  @endauth

  {{-- Carousel Slider --}}
  <div class="relative overflow-hidden mb-12 rounded-lg shadow-md h-[400px]">
    <div id="slider" class="relative w-full h-full">
      @foreach ($slides as $index => $slide)
        <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
          <img src="{{ asset('storage/' . $slide->image_path) }}" class="w-full h-full object-cover rounded-md" alt="Slide Image">
          @auth
            @if(auth()->user()->hasRole('admin'))
              <form action="{{ route('slides.destroy', $slide) }}" method="POST" class="absolute top-4 right-4 z-10">
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

{{-- Bagian 2: Shop by Categories --}}
<h2 class="text-4xl font-heading text-center text-pink-500 font-semibold mb-10">Shop By Categories</h2>

@auth
  @if(auth()->user()->hasRole('admin'))
    <div class="flex justify-end mb-6">
      <button onclick="openAddModal()" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-4 rounded-xl shadow">
        + Tambah Kategori
      </button>
    </div>
  @endif
@endauth

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
  @foreach($categories as $category)
    <div class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-xl transition-shadow duration-300 transform hover:scale-105 border border-rose-200">
      @if($category->image)
        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover">
      @else
        <div class="w-full h-48 bg-pink-200 flex items-center justify-center text-rose-500 font-semibold">No Image</div>
      @endif
      <div class="p-6 text-center">
        <h3 class="text-xl font-semibold text-rose-700">{{ $category->name }}</h3>
        
        @auth
          @if(auth()->user()->hasRole('admin'))
            <div class="mt-4 flex justify-center gap-4">
           <button 
               onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->image ? asset('storage/' . $category->image) : '' }}')" 
               class="bg-yellow-400 hover:bg-yellow-500 text-white py-2 px-4 rounded-md">
                 Edit
            </button>
              <form action="{{ route('categories.destroy', $category) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-400 hover:bg-red-500 text-white py-2 px-4 rounded-md">Delete</button>
              </form>
            </div>
          @endif
        @endauth
      </div>
    </div>
  @endforeach
</div>

<!-- Modal Tambah/Edit -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">
    <h2 id="modalTitle" class="text-2xl font-semibold text-pink-500 mb-4">Tambah Kategori</h2>

    <form id="categoryForm" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="categoryId" name="id">

      <div class="mb-4">
        <label for="name" class="block text-gray-700 font-medium mb-2">Nama Kategori</label>
        <input type="text" id="categoryName" name="name" class="w-full border border-gray-300 rounded-md p-2" required>
      </div>

      <div class="mb-4">
        <label for="image" class="block text-gray-700 font-medium mb-2">Foto</label>
        <input type="file" name="image" id="categoryImage" accept="image/*" class="block w-full">
        <img id="imagePreview" class="mt-2 w-24 h-24 object-cover rounded" style="display: none;" />
      </div>

      <div class="flex justify-end gap-4">
        <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md">Batal</button>
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded-md">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Bagian Rekomendasi Produk --}}
<div class="flex justify-between items-center mt-16 mb-4 px-4 relative">
  <h2 class="text-4xl font-heading text-pink-500 text-center w-full">Rekomendasi Produk ✨</h2>
  @auth
    @if(auth()->user()->hasRole('admin'))
      <button onclick="openCreateModal()" class="absolute right-6 bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
        + Tambah Produk
      </button>
    @endif
  @endauth
</div>

<div class="relative">
  <button onclick="scrollCarousel(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full hover:bg-gray-100 transition">
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
  </button>

  <div id="carouselContainer" class="flex overflow-x-auto scroll-smooth space-x-4 px-10 scrollbar-hide">
    @foreach($rekomendasis as $item)
      <div class="flex-none w-72 bg-white shadow rounded-2xl overflow-hidden border border-pink-300">
        <div class="relative">
          <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-48 object-cover" alt="{{ $item->name }}">
          <span class="absolute top-2 left-2 bg-red-600 text-white text-xs rounded-full px-3 py-1">HOT</span>
        </div>
        <div class="p-4 text-center">
          <h3 class="text-lg font-semibold text-pink-600">{{ $item->name }}</h3>
          <p class="text-sm text-gray-500">{{ $item->category->name ?? 'Kategori Tidak Diketahui' }}</p>
          <p class="text-pink-600 font-bold mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
        </div>

        @auth
          @if(auth()->user()->hasRole('admin'))
            <div class="flex justify-center gap-2 pb-4">
              <button 
                onclick="openEditModal('{{ $item->id }}', '{{ addslashes($item->name) }}', {{ $item->price }}, '{{ addslashes($item->description) }}', '{{ $item->image }}', '{{ $item->category_id }}')" 
                class="text-sm bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition">
                Edit
              </button>
              <form action="{{ route('rekomendasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?');">
                @csrf
                @method('DELETE')
                <button class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Hapus</button>
              </form>
            </div>
          @endif
        @endauth
      </div>
    @endforeach
  </div>

  <button onclick="scrollCarousel(1)" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full hover:bg-gray-100 transition">
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
    </svg>
  </button>
</div>

<!-- Modal Tambah/Edit Produk Rekomendasi -->
<div id="rekomendasiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white p-6 rounded-2xl w-full max-w-md relative">
    <h3 id="modalRekomendasiTitle" class="text-xl font-semibold mb-4 text-pink-600">Tambah Produk</h3>
    <form id="rekomendasiForm" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <label for="namaProduk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
        <input type="text" id="namaProduk" name="name" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
      </div>
      <div class="mb-3">
        <label for="hargaProduk" class="block text-sm font-medium text-gray-700">Harga</label>
        <input type="text" id="hargaProduk" name="price" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
      </div>
      <div class="mb-3">
        <label for="deskripsiProduk" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea id="deskripsiProduk" name="description" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>
      </div>
      <div class="mb-3">
        <label for="kategoriProduk" class="block text-sm font-medium text-gray-700">Kategori</label>
        <select id="kategoriProduk" name="category_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
          @foreach ($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label for="fotoProduk" class="block text-sm font-medium text-gray-700">Gambar</label>
        <input type="file" id="fotoProduk" name="image" class="mt-1 block w-full" accept="image/*" onchange="previewRekomendasiImage(this)">
        <img id="previewRekomendasi" src="" class="mt-2 w-24 h-24 object-cover rounded hidden">
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeRekomendasiModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Batal</button>
        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Simpan</button>
      </div>
    </form>
  </div>
</div>

<section class="max-w-4xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-6 text-[#D77A61]">Komentar Terbaru</h2>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($comments->count() > 0)
        <ul class="space-y-6">
            @foreach ($comments as $comment)
                <li class="p-4 border border-gray-200 rounded shadow-sm bg-white">
                    <p class="text-gray-800">{{ $comment->content }}</p>
                    <div class="mt-2 text-sm text-gray-500 italic">
                        — {{ $comment->user->name ?? 'Anonim' }}, 
                        <time datetime="{{ $comment->created_at }}">{{ $comment->created_at->diffForHumans() }}</time>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            {{ $comments->links() }} {{-- Pagination --}}
        </div>
    @else
        <p class="text-gray-600">Belum ada komentar.</p>
    @endif
</section>

</div>

{{-- Script JS --}}
<script>
  // Slider Otomatis untuk gambar slide
  let currentSlide = 0;
  const slides = document.querySelectorAll('#slider .carousel-slide');
  const totalSlides = slides.length;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove('opacity-100');
      slide.classList.add('opacity-0');
      if (i === index) {
        slide.classList.remove('opacity-0');
        slide.classList.add('opacity-100');
      }
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
  }

  document.addEventListener('DOMContentLoaded', () => {
    showSlide(currentSlide);
    setInterval(nextSlide, 4000);
  });

  // Fungsi buka modal tambah kategori
  function openAddModal() {
    document.getElementById('modalTitle').innerText = 'Tambah Kategori';
    const form = document.getElementById('categoryForm');
    form.action = "{{ route('categories.store') }}";
    form.reset();

    // Hapus input _method kalau ada (supaya gak nambah terus)
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();

    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('categoryModal').classList.remove('hidden');
  }

  // Fungsi buka modal edit kategori
  function openEditModal(id, name, imageUrl) {
    document.getElementById('modalTitle').innerText = 'Edit Kategori';
    const form = document.getElementById('categoryForm');
    form.action = `/categories/${id}`;

    // Tambah input _method PUT kalau belum ada
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
      methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'PUT';
      form.appendChild(methodInput);
    }

    document.getElementById('categoryName').value = name;

    const imgPreview = document.getElementById('imagePreview');
    if (imageUrl) {
      imgPreview.src = imageUrl;
      imgPreview.style.display = 'block';
    } else {
      imgPreview.style.display = 'none';
    }

    document.getElementById('categoryModal').classList.remove('hidden');
  }

  // Fungsi tutup modal
  function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
  }

  // Fungsi scroll carousel produk rekomendasi
  function scrollCarousel(direction) {
    const container = document.getElementById('carouselContainer');
    const scrollAmount = 300; // sesuaikan dengan lebar card + margin
    container.scrollBy({
      left: direction * scrollAmount,
      behavior: 'smooth'
    });
  }

  // Buka modal tambah produk
function openCreateModal() {
  document.getElementById('modalRekomendasiTitle').innerText = 'Tambah Produk';
  const form = document.getElementById('rekomendasiForm');
  form.action = "{{ route('rekomendasi.store') }}";
  form.reset();

  // Hapus input _method jika ada
  const methodInput = form.querySelector('input[name="_method"]');
  if (methodInput) methodInput.remove();

  document.getElementById('previewRekomendasi').style.display = 'none';
  document.getElementById('rekomendasiModal').classList.remove('hidden');
}

// Buka modal edit produk
function openEditModal(id, name, price, description, image, category_id) {
  document.getElementById('modalRekomendasiTitle').innerText = 'Edit Produk';
  const form = document.getElementById('rekomendasiForm');
  form.action = `/rekomendasi/${id}`;

  let methodInput = form.querySelector('input[name="_method"]');
  if (!methodInput) {
    methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PUT';
    form.appendChild(methodInput);
  }

  document.getElementById('namaProduk').value = name;
  document.getElementById('hargaProduk').value = formatToRupiah(price);
  document.getElementById('deskripsiProduk').value = description;
  document.getElementById('kategoriProduk').value = category_id;

  const previewImg = document.getElementById('previewRekomendasi');
  previewImg.src = `/storage/${image}`;
  previewImg.classList.remove('hidden');

  document.getElementById('rekomendasiModal').classList.remove('hidden');
}

// Tutup modal
function closeRekomendasiModal() {
  document.getElementById('rekomendasiModal').classList.add('hidden');
}

// Preview gambar
function previewRekomendasiImage(input) {
  const preview = document.getElementById('previewRekomendasi');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      preview.src = e.target.result;
      preview.classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Format harga otomatis ke Rupiah
function formatToRupiah(value) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value).replace(',00', '');
}

document.getElementById('hargaProduk')?.addEventListener('input', function (e) {
  let rawValue = e.target.value.replace(/\D/g, '');
  e.target.value = formatToRupiah(rawValue);
});

</script>

{{-- Style --}}
<style>
  @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Inter:wght@400;600&display=swap');
  .font-heading {
    font-family: 'Great Vibes', cursive;
  }
  .scrollbar-hide::-webkit-scrollbar { display: none; }
  .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
