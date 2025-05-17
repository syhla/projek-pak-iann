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

/* Hide scrollbar for WebKit-based browsers */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;      /* Firefox */
}

/* Biar gambar preview tidak terlalu besar dan proporsional */
#createImagePreview,
#editImagePreview {
  max-width: 100%;
  height: 150px;       /* tinggi tetap */
  object-fit: cover;   /* proporsi gambar tetap bagus */
  border-radius: 0.5rem; /* rounded */
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

      <!-- Button Tambah Produk -->
<div class="flex justify-end mb-4 px-10">
  <button onclick="openCreateModal()" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
    + Tambah Produk
  </button>
</div>

<!-- Section Rekomendasi Produk -->
<div class="relative">
  <!-- Tombol kiri -->
  <button onclick="scrollCarousel(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full hover:bg-gray-100 transition">
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
  </button>

  <!-- Wrapper scroll -->
  <div id="carouselContainer" class="flex overflow-x-auto scroll-smooth space-x-4 px-10 scrollbar-hide">
    @foreach($rekomendasis as $item)
    <div class="flex-none w-1/4">
      <div class="bg-white shadow rounded-2xl overflow-hidden">
        <div class="relative">
          <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-48 object-cover" alt="{{ $item->name }}">
          <span class="absolute top-2 left-2 bg-red-600 text-white text-xs rounded-full px-3 py-1">HOT</span>
        </div>
        <div class="p-4 text-center">
          <h3 class="text-lg font-semibold text-pink-600">{{ $item->name }}</h3>
          <p class="text-sm text-gray-500">{{ $item->description }}</p>
          <p class="text-pink-600 font-bold mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
        </div>
        <div class="flex justify-center gap-2 pb-4">
          <button 
            onclick="openEditModal('{{ $item->id }}', '{{ addslashes($item->name) }}', {{ $item->price }}, '{{ addslashes($item->description) }}', '{{ $item->image }}')" 
            class="text-sm bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition">
            Edit
          </button>
          <form action="{{ route('rekomendasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?');">
            @csrf
            @method('DELETE')
            <button class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Hapus</button>
          </form>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Tombol kanan -->
  <button onclick="scrollCarousel(1)" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full hover:bg-gray-100 transition">
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
    </svg>
  </button>
</div>

<!-- Modal Tambah Produk -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-xl shadow-lg w-96 p-6 relative">
    <h2 class="text-xl font-semibold mb-4 text-center text-pink-600">Tambah Produk</h2>
    <form action="{{ route('rekomendasi.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <label class="block mb-2 font-semibold text-gray-700">Nama Produk</label>
      <input type="text" name="name" required class="w-full border rounded px-3 py-2 mb-3 focus:outline-pink-500" />

      <label class="block mb-2 font-semibold text-gray-700">Harga</label>
      <input type="number" name="price" required class="w-full border rounded px-3 py-2 mb-3 focus:outline-pink-500" />

      <label class="block mb-2 font-semibold text-gray-700">Deskripsi</label>
      <textarea name="description" required class="w-full border rounded px-3 py-2 mb-3 focus:outline-pink-500"></textarea>

      <label class="block mb-2 font-semibold text-gray-700">Gambar</label>
      <input type="file" name="image" accept="image/*" required class="w-full mb-4" onchange="previewCreateImage(event)" />
      <img id="createImagePreview" class="w-full h-48 object-cover rounded mb-4 hidden" alt="Preview Gambar" />

      <div class="flex justify-end gap-3">
        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 transition">Batal</button>
        <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600 transition">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Produk -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-xl shadow-lg w-96 p-6 relative">
    <h2 class="text-xl font-semibold mb-4 text-center text-pink-600">Edit Produk</h2>
    <form id="editForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <label class="block mb-2 font-semibold text-gray-700">Nama Produk</label>
      <input id="editName" type="text" name="name" required class="w-full border rounded px-3 py-2 mb-3 focus:outline-pink-500" />

      <label class="block mb-2 font-semibold text-gray-700">Harga</label>
      <input id="editPrice" type="number" name="price" required class="w-full border rounded px-3 py-2 mb-3 focus:outline-pink-500" />

      <label class="block mb-2 font-semibold text-gray-700">Deskripsi</label>
      <textarea id="editDescription" name="description" required class="w-full border rounded px-3 py-2 mb-3 focus:outline-pink-500"></textarea>

      <label class="block mb-2 font-semibold text-gray-700">Gambar</label>
      <input id="editImage" type="file" name="image" accept="image/*" class="w-full mb-4" />
      <img id="editImagePreview" class="w-full h-48 object-cover rounded mb-4" alt="Preview Gambar" />

      <div class="flex justify-end gap-3">
        <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 transition">Batal</button>
        <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600 transition">Update</button>
      </div>
    </form>
  </div>
</div>
</div>

<script>
 // Modal Create
  function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
  }
  function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    clearCreateForm();
  }
  function clearCreateForm() {
    document.querySelector('#createModal form').reset();
    document.getElementById('createImagePreview').src = '';
    document.getElementById('createImagePreview').classList.add('hidden');
  }
  function previewCreateImage(event) {
    const preview = document.getElementById('createImagePreview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.classList.remove('hidden');
  }

  // Modal Edit
  function openEditModal(id, name, price, description, imageUrl) {
    const modal = document.getElementById('editModal');
    modal.classList.remove('hidden');

    document.getElementById('editName').value = name;
    document.getElementById('editPrice').value = price;
    document.getElementById('editDescription').value = description;
    document.getElementById('editImagePreview').src = imageUrl ? `/storage/${imageUrl}` : '';

    const form = document.getElementById('editForm');
    form.action = `/rekomendasi/${id}`;
  }
  function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
  }

  // Preview gambar edit
  document.getElementById('editImage').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (evt) {
        document.getElementById('editImagePreview').src = evt.target.result;
      }
      reader.readAsDataURL(file);
    }
  });

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
    setInterval(nextSlide, 4000);

</script>
@endsection
