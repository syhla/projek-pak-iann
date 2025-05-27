@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-olive-dark text-3xl">🍃 Produk Baru</h1>

    <a href="{{ route('admin.newproducts.create') }}" class="btn btn-olive rounded-3 text-white font-bold mb-3 px-4">+ Tambah Produk</a>

    @if(session('success'))
        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>Judul</th>
                    <th>Gambar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($newProducts as $product)
                    <tr>
                        <td class="align-middle">{{ $product->title }}</td>
                        <td class="align-middle text-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="100" class="rounded shadow-sm">
                            @else
                                <span class="text-muted fst-italic">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="align-middle text-center">
                            <a href="{{ route('admin.newproducts.edit', $product) }}" class="btn btn-sm btn-outline-olive rounded-2 me-1">✏️ Edit</a>
                            <form action="{{ route('admin.newproducts.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger rounded-2">🗑️ Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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

    .btn-outline-olive {
        color: #556B2F;
        border: 1px solid #556B2F;
        background-color: transparent;
    }

    .btn-outline-olive:hover {
        background-color: #556B2F;
        color: white;
    }
</style>
@endsection
