@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    {{-- Section Judul --}}
    <div class="text-center mb-5">
        <p style="color: #0f6c3c; font-style: italic; font-size: 1.1rem; margin-bottom: 0;">Our</p>
        <div class="d-flex justify-content-center align-items-center mt-1">
            <div style="height: 4px; width: 60px; background-color: #807e7e;"></div>
            <h1 class="mx-3 fw-bold text-[#D4AF37]" style="font-size: 2rem;">PROMO</h1>
            <div style="height: 4px; width: 60px; background-color: #807e7e;"></div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Tambah --}}
    @auth
        @if(auth()->user()->role === 'admin')
            <div class="mb-4">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPromo">
                    + Tambah Promo
                </button>
            </div>
        @endif
    @endauth

<div class="row row-cols-1 row-cols-md-4 g-4">
    @forelse($promos as $promo)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm rounded-4 position-relative">

                {{-- Badge Diskon --}}
                @if($promo->discount_percentage)
                    <span class="position-absolute top-0 start-0 m-2 badge bg-danger">
                        Diskon {{ rtrim(rtrim(number_format($promo->discount_percentage, 1, '.', ''), '0'), '.') }}%
                    </span>
                @endif

                {{-- Gambar --}}
                @if($promo->gambar)
                    <img src="{{ asset($promo->gambar) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif

                {{-- Konten --}}
                <div class="card-body d-flex flex-column text-center">
                    {{-- Nama Promo --}}
                    <h4 class="fw-bold text-2xl text-[#0f6c3c]" style="">{{ $promo->title }}</h4>

                    {{-- Harga --}}
                    <div class="mb-2 mt-2 text-xl">
                        @if($promo->original_price && $promo->discount_percentage)
                            @php
                                $hargaDiskon = floor($promo->original_price * (1 - $promo->discount_percentage / 100));
                            @endphp
                            <span class="text-decoration-line-through text-muted ">
                                Rp{{ number_format($promo->original_price, 0, ',', '.') }}
                            </span>
                            <span class="fw-bold text-[#D4AF37] ms-1">
                                Rp{{ number_format($hargaDiskon, 0, ',', '.') }}
                            </span>
                        @elseif($promo->original_price)
                            <span class="fw-bold text-[#D4AF37]">
                                Rp{{ number_format($promo->original_price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>



                    {{-- Rating Dummy --}}
<div class="text-warning mb-2 text-2xl">
    ★★★★★
</div>

                    {{-- Tanggal Promo --}}
                    <p class="text-muted text-xs mb-3">
                        {{ \Carbon\Carbon::parse($promo->tanggal_mulai)->translatedFormat('d M Y') }} –
                        {{ \Carbon\Carbon::parse($promo->tanggal_akhir)->translatedFormat('d M Y') }}
                    </p>

                    {{-- Tombol Add to Cart --}}
<form action="{{ route('customer.cart.add', $promo->id) }}" method="POST" class="mt-auto">
    @csrf
    <button type="submit"
        class="p-2 w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm bg-[#D4AF37] hover:bg-[#c6a32e] text-white fw-semibold"
        style="border-radius: 10px;">
        <i class="bi bi-cart-plus-fill"></i> Add to Cart
    </button>
</form>
                </div>
                {{-- Tombol Admin --}}
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="card-footer d-flex justify-content-end gap-2 bg-white border-top-0">
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditPromo{{ $promo->id }}">Edit</button>
                            <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    @endif
                @endauth

            </div>
        </div>
    @empty
        <p class="text-center text-muted">Tidak ada promo tersedia.</p>
    @endforelse
</div>


{{-- Modal Edit Promo --}}
@foreach($promos as $promo)
    <div class="modal fade" id="modalEditPromo{{ $promo->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-3 border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Edit Promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.promos.update', $promo->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label">Judul Promo</label>
                                    <input type="text" name="title" class="form-control" value="{{ $promo->title }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga Awal</label>
                                    <input type="number" name="original_price" class="form-control" value="{{ $promo->original_price }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Diskon (%)</label>
                                    <input type="number" name="discount_percentage" class="form-control" value="{{ $promo->discount_percentage }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $promo->tanggal_mulai }}">
                                </div>

                            </div>
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ $promo->tanggal_akhir }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Upload Gambar (opsional)</label>
                                    <input type="file" name="gambar" class="form-control">
                                    @if($promo->gambar)
                                        <img src="{{ asset($promo->gambar) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update Promo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- Modal Tambah Promo --}}
<div class="modal fade" id="modalTambahPromo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3 border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Tambah Promo Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label">Judul Promo</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Awal</label>
                                <input type="number" name="original_price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Diskon (%)</label>
                                <input type="number" name="discount_percentage" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Gambar</label>
                                <input type="file" name="gambar" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
