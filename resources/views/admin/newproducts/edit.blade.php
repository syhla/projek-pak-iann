@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-olive-dark text-3xl">✏️ Edit Produk</h1>

    <form action="{{ route('admin.newproducts.update', $newproduct) }}" method="POST" enctype="multipart/form-data" class="p-4 rounded shadow-sm bg-light">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Judul Produk</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $newproduct->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label fw-semibold">Harga Produk</label>
            <input type="number" name="price" class="form-control" min="0" step="100" value="{{ old('price', $newproduct->price) }}" required>
        </div>

        <div class="mb-3">
    <label for="stock" class="form-label text-olive">Stok Produk</label>
    <input type="number" name="stock" class="form-control border-olive rounded-3" value="{{ old('stock', $newproduct->stock ?? 0) }}" min="0" required>
</div>


        <div class="mb-3">
            <label for="image" class="form-label fw-semibold">Gambar Baru (opsional)</label>
            <input type="file" name="image" class="form-control">
            @if ($newproduct->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $newproduct->image) }}" width="120" class="rounded shadow">
                </div>
            @endif
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_today_available" class="form-check-input" id="availableToday"
                   {{ old('is_today_available', $newproduct->is_today_available) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="availableToday">Tampilkan di Produk Tersedia Hari Ini</label>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_menu_displayed" class="form-check-input" id="menuDisplay"
                   {{ old('is_menu_displayed', $newproduct->is_menu_displayed) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="menuDisplay">Tampilkan di Halaman Menu</label>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-olive px-4 rounded-3">💾 Update</button>
            <a href="{{ route('admin.newproducts.index') }}" class="btn btn-outline-secondary px-4 rounded-3">❌ Batal</a>
        </div>
    </form>
</div>

<style>
    .text-olive-dark {
        color: #556B2F;
    }

    .btn-olive {
        background-color: #556B2F;
        color: white;
        border: none;
    }

    .btn-olive:hover {
        background-color: #445a24;
    }
</style>
@endsection
