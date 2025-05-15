@extends('layouts.app')

@section('content')
<style>
    .carousel-slide {
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .carousel-slide.opacity-100 {
        opacity: 1;
    }
</style>

<div class="bg-white py-12 px-6">

{{-- Form Upload Gambar untuk Slide --}}
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
<div class="relative overflow-hidden mb-12 rounded-lg shadow-md">
    <div id="slider" class="relative w-full h-[500px]">
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

  {{-- Tombol Create Category --}}
  @auth
    @if(auth()->user()->hasRole('admin'))
    <div class="mb-8 text-center">
      <button onclick="openCreateCategoryModal()" class="bg-rose-400 hover:bg-rose-500 text-white py-2 px-4 rounded-md">Create New Category</button>
    </div>
    @endif
  @endauth

  {{-- Kategori Produk --}}
  <div class="py-12 px-6">
    <h2 class="text-4xl font-bold text-center mb-10 text-rose-600">Shop By Categories 🎀</h2>
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
              <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->image ? asset('storage/' . $category->image) : '' }}')" class="bg-yellow-400 hover:bg-yellow-500 text-white py-2 px-4 rounded-md">Edit</button>
              <form action="{{ route('categories.destroy', $category) }}" method="POST" class="mt-2">
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
  </div>

  {{-- Modal Create Category --}}
  <div id="createCategoryModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 hidden z-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
          <h2 class="text-2xl font-bold mb-4 text-rose-600">Create New Category</h2>
          <form id="createCategoryForm" method="POST" enctype="multipart/form-data" action="{{ route('categories.store') }}">
              @csrf
              <div class="mb-4">
                  <label for="name" class="block mb-1 font-semibold text-rose-700">Category Name</label>
                  <input type="text" name="name" class="w-full p-2 border rounded-md" required>
              </div>
              <div class="mb-4">
                  <label for="image" class="block mb-1 font-semibold text-rose-700">Image</label>
                  <input type="file" name="image" class="w-full p-2 border rounded-md">
              </div>
              <div class="flex justify-end gap-2">
                  <button type="button" onclick="closeCreateCategoryModal()" class="bg-red-400 hover:bg-red-500 text-white py-2 px-4 rounded-md">Close</button>
                  <button type="submit" class="bg-blue-400 hover:bg-blue-500 text-white py-2 px-4 rounded-md">Create</button>
              </div>
          </form>
      </div>
  </div>

  {{-- Modal Edit Category --}}
  <div id="editCategoryModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
      <h2 class="text-2xl font-bold mb-4 text-rose-600">Edit Category</h2>
      <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
          <label class="block mb-1 font-semibold text-rose-700">Category Name</label>
          <input type="text" name="name" id="editCategoryName" class="w-full p-2 border rounded-md" required>
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold text-rose-700">Image</label>
          <input type="file" name="image" id="editCategoryImage" class="w-full p-2 border rounded-md">
          <img id="editCategoryImagePreview" src="" alt="Image Preview" class="mt-2 w-24 h-24 object-cover rounded border">
        </div>
<div class="flex">
  <div class="ml-auto flex gap-2">
    <button type="button" onclick="closeEditModal()" class="bg-red-400 hover:bg-red-500 text-white py-2 px-4 rounded-md">Close</button>
    <button type="submit" class="bg-blue-400 hover:bg-blue-500 text-white py-2 px-4 rounded-md">Save</button>
  </div>
</div>
      </form>
    </div>
  </div>

  {{-- Produk Rekomendasi --}}
<div class="py-12 px-6 bg-pink-50 rounded-xl mt-16 shadow-lg">
  <h2 class="text-4xl font-extrabold text-center mb-10 text-pink-600 animate-pulse">Produk Rekomendasi Kocak! 🎉🤣</h2>

  {{-- Tombol Tambah Produk --}}
  @auth
    @if(auth()->user()->hasRole('admin'))
    <div class="mb-6 text-center">
      <button onclick="openCreateRekomendasiModal()" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transform hover:scale-110 transition-transform">
        Tambah Produk Rekomendasi 😜
      </button>
    </div>
    @endif
  @endauth

  {{-- List Produk Rekomendasi --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @foreach($rekomendasis as $item)
    <div class="bg-white rounded-2xl p-5 shadow-2xl border-4 border-pink-400 hover:shadow-pink-500 hover:scale-105 transition-transform duration-300 relative">
      <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-40 object-cover rounded-xl mb-4 border-2 border-pink-300">
      <h3 class="text-xl font-extrabold text-pink-700 mb-2">{{ $item->name }}</h3>
      <p class="text-pink-600 font-semibold mb-4">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
      <p class="italic text-pink-500 text-sm mb-4">"{!! $item->description !!}"</p>

      @auth
        @if(auth()->user()->hasRole('admin'))
        <div class="flex justify-between absolute bottom-3 left-3 right-3">
          <button onclick="openEditRekomendasiModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ $item->price }}', '{{ addslashes($item->description) }}', '{{ asset('storage/' . $item->image) }}')" class="bg-yellow-400 hover:bg-yellow-500 text-white py-1 px-3 rounded-full text-sm font-bold shadow-md">Edit ✏️</button>
          <form action="{{ route('rekomendasi.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin mau hapus produk kocak ini? 😆');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-400 hover:bg-red-500 text-white py-1 px-3 rounded-full text-sm font-bold shadow-md">Hapus 🗑️</button>
          </form>
        </div>
        @endif
      @endauth
    </div>
    @endforeach
  </div>
</div>

{{-- Modal Create Produk Rekomendasi --}}
<div id="createRekomendasiModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
  <div class="bg-white p-6 rounded-xl shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-4 text-pink-600">Tambah Produk Rekomendasi</h2>
    <form id="createRekomendasiForm" method="POST" action="{{ route('rekomendasi.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Nama Produk</label>
        <input type="text" name="name" class="w-full p-2 border rounded-md" required>
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Harga (Rp)</label>
        <input type="number" name="price" class="w-full p-2 border rounded-md" required min="0">
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Deskripsi</label>
        <textarea name="description" rows="3" class="w-full p-2 border rounded-md" required></textarea>
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Gambar Produk</label>
        <input type="file" name="image" class="w-full p-2 border rounded-md" required>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeCreateRekomendasiModal()" class="bg-red-400 hover:bg-red-500 text-white py-2 px-4 rounded-md">Tutup</button>
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded-md">Tambah</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit Produk Rekomendasi --}}
<div id="editRekomendasiModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
  <div class="bg-white p-6 rounded-xl shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-4 text-pink-600">Edit Produk Rekomendasi</h2>
    <form id="editRekomendasiForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Nama Produk</label>
        <input type="text" name="name" id="editRekomendasiName" class="w-full p-2 border rounded-md" required>
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Harga (Rp)</label>
        <input type="number" name="price" id="editRekomendasiPrice" class="w-full p-2 border rounded-md" required min="0">
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Deskripsi</label>
        <textarea name="description" id="editRekomendasiDescription" rows="3" class="w-full p-2 border rounded-md" required></textarea>
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Ganti Gambar Produk</label>
        <input type="file" name="image" id="editRekomendasiImage" class="w-full p-2 border rounded-md">
        <img id="editRekomendasiImagePreview" src="" alt="Preview Gambar" class="mt-2 w-28 h-28 object-cover rounded border border-pink-300">
      </div>
      <div class="flex justify-between">
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded-md font-bold">Simpan</button>
        <button type="button" onclick="closeEditRekomendasiModal()" class="bg-red-400 hover:bg-red-500 text-white py-2 px-4 rounded-md font-bold">Tutup</button>
      </div>
    </form>
  </div>
</div>

{{-- List Produk Rekomendasi --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @foreach($rekomendasis as $item)
    <div class="bg-white rounded-2xl p-5 shadow-2xl border-4 border-pink-400 hover:shadow-pink-500 hover:scale-105 transition-transform duration-300 relative">
      <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-40 object-cover rounded-xl mb-4 border-2 border-pink-300">
      <h3 class="text-xl font-extrabold text-pink-700 mb-2">{{ $item->name }}</h3>
      <p class="text-pink-600 font-semibold mb-4">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
      <p class="italic text-pink-500 text-sm mb-4">"{!! $item->description !!}"</p>

      @auth
        @if(auth()->user()->hasRole('admin'))
        <div class="flex justify-between absolute bottom-3 left-3 right-3">
          <button onclick="openEditRekomendasiModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ $item->price }}', '{{ addslashes($item->description) }}', '{{ asset('storage/' . $item->image) }}')" class="bg-yellow-400 hover:bg-yellow-500 text-white py-1 px-3 rounded-full text-sm font-bold shadow-md">Edit ✏</button>
          <form action="{{ route('rekomendasi.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin mau hapus produk kocak ini? 😆');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-400 hover:bg-red-500 text-white py-1 px-3 rounded-full text-sm font-bold shadow-md">Hapus 🗑</button>
          </form>
        </div>
        @endif
      @endauth
    </div>
    @endforeach
  </div>

{{-- Modal Create Produk Rekomendasi --}}
<div id="createRekomendasiModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
  <div class="bg-white p-6 rounded-xl shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-4 text-pink-600">Tambah Produk Rekomendasi</h2>
    <form id="createRekomendasiForm" method="POST" action="{{ route('rekomendasi.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Nama Produk</label>
        <input type="text" name="name" class="w-full p-2 border rounded-md" required>
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Harga (Rp)</label>
        <input type="number" name="price" class="w-full p-2 border rounded-md" required min="0">
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Deskripsi</label>
        <textarea name="description" rows="3" class="w-full p-2 border rounded-md" required></textarea>
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Gambar Produk</label>
        <input type="file" name="image" class="w-full p-2 border rounded-md" required>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeCreateRekomendasiModal()" class="bg-red-400 hover:bg-red-500 text-white py-2 px-4 rounded-md">Tutup</button>
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded-md">Tambah</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit Produk Rekomendasi --}}
<div id="editRekomendasiModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
  <div class="bg-white p-6 rounded-xl shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-4 text-pink-600">Edit Produk Rekomendasi</h2>
    <form id="editRekomendasiForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Nama Produk</label>
        <input type="text" name="name" id="editRekomendasiName" class="w-full p-2 border rounded-md" required>
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Harga (Rp)</label>
        <input type="number" name="price" id="editRekomendasiPrice" class="w-full p-2 border rounded-md" required min="0">
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Deskripsi</label>
        <textarea name="description" id="editRekomendasiDescription" rows="3" class="w-full p-2 border rounded-md" required></textarea>
      </div>
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-pink-700">Ganti Gambar Produk</label>
        <input type="file" name="image" id="editRekomendasiImage" class="w-full p-2 border rounded-md">
        <img id="editRekomendasiImagePreview" src="" alt="Preview Gambar" class="mt-2 w-28 h-28 object-cover rounded border border-pink-300">
      </div>
      <div class="flex justify-between">
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded-md font-bold">Simpan</button>
        <button type="button" onclick="closeEditRekomendasiModal()" class="bg-red-400 hover:bg-red-500 text-white py-2 px-4 rounded-md font-bold">Tutup</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openCreateCategoryModal() {
      document.getElementById('createCategoryModal').classList.remove('hidden');
    }

    function closeCreateCategoryModal() {
      document.getElementById('createCategoryModal').classList.add('hidden');
    }

    function openEditModal(id, name, imageUrl) {
      const modal = document.getElementById('editCategoryModal');
      modal.classList.remove('hidden');

      document.getElementById('editCategoryName').value = name;
      document.getElementById('editCategoryImagePreview').src = imageUrl || '';

      const form = document.getElementById('editCategoryForm');
      form.action = `/categories/${id}`; // Ubah sesuai route update kamu jika perlu
    }

    function closeEditModal() {
      document.getElementById('editCategoryModal').classList.add('hidden');
    }

    // Preview gambar saat upload di modal edit kategori
    document.getElementById('editCategoryImage').addEventListener('change', function(e){
      const file = e.target.files[0];
      if(file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
          document.getElementById('editCategoryImagePreview').src = evt.target.result;
        }
        reader.readAsDataURL(file);
      }
    });

  // --- Produk Rekomendasi Modal ---
  function openCreateRekomendasiModal() {
    document.getElementById('createRekomendasiModal').classList.remove('hidden');
  }
  function closeCreateRekomendasiModal() {
    document.getElementById('createRekomendasiModal').classList.add('hidden');
  }

  function openEditRekomendasiModal(id, name, price, description, imageUrl) {
    const modal = document.getElementById('editRekomendasiModal');
    modal.classList.remove('hidden');

    document.getElementById('editRekomendasiName').value = name;
    document.getElementById('editRekomendasiPrice').value = price;
    document.getElementById('editRekomendasiDescription').value = description;

    // Set form action
    const form = document.getElementById('editRekomendasiForm');
    form.action = `/rekomendasi/${id}`; // Sesuaikan route jika berbeda

    // Preview gambar di modal edit produk rekomendasi
    const preview = document.getElementById('editRekomendasiImagePreview');
    preview.src = imageUrl;
  }

  function closeEditRekomendasiModal() {
    document.getElementById('editRekomendasiModal').classList.add('hidden');
  }

  // Preview gambar saat upload di modal edit produk rekomendasi
  document.getElementById('editRekomendasiImage').addEventListener('change', function(e){
    const file = e.target.files[0];
    if(file) {
      const reader = new FileReader();
      reader.onload = function(evt) {
        document.getElementById('editRekomendasiImagePreview').src = evt.target.result;
      }
      reader.readAsDataURL(file);
    }
  });

   // Slider otomatis setiap 5 detik
let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');

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
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    showSlide(currentSlide);
    setInterval(nextSlide, 4000);</script>
@endsection
