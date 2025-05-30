@extends('layouts.app')

@section('content')
{{-- Hero / Deskripsi Kategori --}}
<section class="bg-[#9fb774] py-20">
    <div class="container mx-auto px-6 max-w-6xl flex flex-col lg:flex-row items-start gap-12">
        
        {{-- Gambar --}}
        <div class="lg:w-1/2 w-full">
            <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('default.jpg') }}"
                 alt="{{ $category->name }}"
                 class="rounded-3xl shadow-lg w-full h-80 object-cover">
        </div>

        {{-- Deskripsi --}}
        <div class="lg:w-1/2 w-full flex flex-col justify-start">
            <h1 class="text-4xl font-extrabold text-[#FDF6EC] uppercase mb-2">{{ $category->name }}</h1>
            <div class="w-20 h-2 bg-[#6e4f11] rounded-full mb-6"></div>
            <p class="text-lg font-medium leading-relaxed text-[#FDF6EC]">
                {!! nl2br(e($category->description ?? 'Kategori spesial kami dengan pilihan rasa terbaik.')) !!}
            </p>
        </div>
    </div>
</section>

{{-- Daftar Produk --}}
<section class="bg-[#FDF6EC] py-20">
    <div class="container mx-auto px-6 max-w-6xl">
        <h2 class="text-center text-3xl font-bold text-[#556B2F] mb-14 tracking-wide">
            Menu {{ $category->name }}
        </h2>

        @if ($category->products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($category->products as $product)
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-[#B2C8BA] hover:shadow-lg transition duration-300 flex flex-col">
                        <img src="{{ $product->image ? asset($product->image) : asset('default-product.jpg') }}"
                             alt="{{ $product->name }}"
                             class="w-full h-44 object-cover">

                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-[#556B2F] uppercase mb-1">{{ $product->name }}</h3>

                            @if ($product->original_price)
                                <p class="text-gray-400 text-sm line-through mb-1">
                                    Rp {{ number_format($product->original_price, 0, ',', '.') }}
                                </p>
                            @endif

                            <p class="text-[#CBA135] text-lg font-extrabold mb-3">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>

      <div class="text-xl mb-4 flex items-center text-yellow-500">
        ★★★★★
      </div>

                            {{-- Add to Cart --}}
                            <form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="mt-auto">
                                @csrf
                                <button type="submit"
                                    class="bg-[#556B2F] hover:bg-[#44552e] text-white font-semibold py-2 rounded-xl w-full transition duration-300">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-[#556B2F] mt-10 italic font-medium">
                Belum ada produk dalam kategori ini.
            </p>
        @endif
    </div>
</section>
@endsection
