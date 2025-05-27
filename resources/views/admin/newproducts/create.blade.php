@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold text-olive-dark text-3xl">🍃 Tambah Produk Baru</h2>

    <form action="{{ route('admin.newproducts.store') }}" method="POST" enctype="multipart/form-data" class="border border-olive rounded-4 p-4 shadow-sm bg-light">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label text-olive">Judul Produk</label>
            <input type="text" name="title" class="form-control border-olive rounded-3" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label text-olive">Harga Produk (Rp)</label>
            <input type="number" name="price" class="form-control border-olive rounded-3" value="{{ old('price') }}" step="100" min="0" required>
        </div>

        <div class="mb-3">
    <label for="stock" class="form-label text-olive">Stok Produk</label>
    <input type="number" name="stock" class="form-control border-olive rounded-3" value="{{ old('stock', $newproduct->stock ?? 0) }}" min="0" required>
</div>

        <div class="mb-3">
            <label for="image" class="form-label text-olive">Gambar Produk (opsional)</label>
            <input type="file" name="image" class="form-control border-olive rounded-3">
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label text-olive">Pilih Kategori</label>
            <select name="category_id" class="form-control border-olive rounded-3" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_today_available" class="form-check-input" id="availableToday">
            <label class="form-check-label text-olive" for="availableToday">
                Tampilkan di halaman welcome
            </label>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_menu_displayed" class="form-check-input" id="menuDisplay">
            <label class="form-check-label text-olive" for="menuDisplay">
                Tampilkan di Halaman Menu
            </label>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-olive rounded-3 px-4 text-white font-bold">✅ Simpan</button>
            <a href="{{ route('admin.newproducts.index') }}" class="btn btn-outline-olive rounded-3 px-4">↩️ Kembali</a>
        </div>
    </form>
</div>

<style>
    .text-olive-dark {
        color: #556B2F;
    }

    .text-olive {
        color: #6b8e23;
    }

    .border-olive {
        border-color: #556B2F !important;
    }

    .btn-olive {
        background-color: #556B2F;
        color: white;
        border: none;
    }

    .btn-olive:hover {
        background-color: #445a24;
    }

    .btn-outline-olive {
        color: #556B2F;
        border: 1px solid #556B2F;
        background-color: transparent;
    }

    .btn-outline-olive:hover {
        background-color: #556B2F;
        color: white;
    }

    .form-control:focus {
        border-color: #556B2F;
        box-shadow: 0 0 0 0.2rem rgba(85, 107, 47, 0.25);
    }
</style>
@endsection
