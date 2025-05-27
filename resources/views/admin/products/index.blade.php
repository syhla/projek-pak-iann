@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8" x-data="{ showCreateModal: false, editModalId: null }">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Header -->
        <div class="px-6 py-6">
            <h1 class="text-4xl font-bold text-[#b8860b]">Kelola Produk</h1>
            <div class="flex justify-end mt-8">
                <button @click="showCreateModal = true"
                    class="bg-[#b8860b] text-white px-4 py-2 rounded-lg hover:bg-[#c4a24c] transition font-semibold">
                    + Tambah Produk
                </button>
            </div>
        </div>

        <!-- Daftar Produk -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
            <div class="bg-white rounded-xl shadow-md p-4">
                @if ($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                    class="w-full h-48 object-cover rounded mb-3">
                @endif

                <h3 class="font-bold text-2xl text-[#7b6d1e] mb-1">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm mb-2 mt-2">{{ $product->description }}</p>
                <p class="text-[#7b6d1e] font-semibold mb-3 mt-6">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                <div class="flex justify-end gap-4 text-sm mt-4">
                    <button @click="editModalId = {{ $product->id }}" class="text-blue-600 hover:underline">Edit</button>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Hapus produk ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Produk -->
            <div x-show="editModalId === {{ $product->id }}" x-transition x-cloak
                class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 overflow-auto">
                <div class="bg-white rounded-xl p-6 w-full max-w-xl shadow-lg max-h-[90vh] overflow-y-auto">
                    <h2 class="text-xl font-bold text-[#b8860b] mb-4">Edit Produk</h2>
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <input type="text" name="name" value="{{ $product->name }}"
                            class="w-full border p-2 rounded mb-2" placeholder="Nama Produk">
                        <textarea name="description" class="w-full border p-2 rounded mb-2">{{ $product->description }}</textarea>
                        <input type="number" name="price" step="0.01" value="{{ $product->price }}"
                            class="w-full border p-2 rounded mb-2" placeholder="Harga">
                        <input type="number" name="stock" value="{{ $product->stock ?? 0 }}" min="0"
                            class="w-full border p-2 rounded mb-2" placeholder="Stok Produk">
                        <input type="file" name="image" class="w-full mb-2">                            
                        <div class="space-y-1 text-sm text-[#7b6d1e] font-medium mt-3">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                                Tampilkan di Produk Baru
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_today_available" {{ $product->is_today_available ? 'checked' : '' }}>
                                Tersedia Hari Ini
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_recommended" {{ $product->is_recommended ? 'checked' : '' }}>
                                Rekomendasi
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="show_on_welcome" {{ $product->show_on_welcome ? 'checked' : '' }}>
                                Tampilkan di Halaman Welcome
                            </label>
                        </div>

                        <label class="block mt-4 text-sm font-semibold text-[#7b6d1e]">Kategori</label>
                        <select name="category_id" class="w-full border p-2 rounded">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="editModalId = null" class="text-gray-500 hover:underline">Batal</button>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Modal Tambah Produk -->
        <div x-show="showCreateModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl p-6 w-full max-w-xl shadow-lg max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-bold text-[#b8860b] mb-4">Tambah Produk Baru</h2>
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="text" name="name" class="w-full border p-2 rounded mb-2" placeholder="Nama Produk">
                    <textarea name="description" class="w-full border p-2 rounded mb-2" placeholder="Deskripsi"></textarea>
                    <input type="number" name="price" step="0.01" class="w-full border p-2 rounded mb-2" placeholder="Harga">
                    <input type="number" name="stock" value="0" min="0"
                     class="w-full border p-2 rounded mb-2" placeholder="Stok Produk">
                    <input type="file" name="image" class="w-full mb-2">

                    <div class="space-y-1 text-sm text-[#7b6d1e] font-medium mt-3">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_today_available"> Tersedia Hari Ini
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_recommended"> Rekomendasi
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="show_on_welcome"> Tampilkan di Halaman Welcome
                        </label>
                    </div>

                    <label class="block mt-4 text-sm font-semibold text-[#7b6d1e]">Kategori</label>
                    <select name="category_id" class="w-full border p-2 rounded">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="showCreateModal = false" class="text-gray-500 hover:underline">Batal</button>
                        <button type="submit" class="bg-[#b8860b] text-white px-4 py-2 rounded hover:bg-[#c4a24c]">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
