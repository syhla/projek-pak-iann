@extends('layouts.app') {{-- Pastikan file layouts/app.blade.php tersedia --}}
@section('content')

{{-- Hero Section --}}
<section class="text-center py-16 px-6 bg-[#FFF5EC]">
    <h1 class="text-4xl md:text-5xl font-logo text-[#4B2E2E] mb-6">Hello! We are Namibekery</h1>
    <p class="max-w-3xl mx-auto text-lg leading-relaxed text-[#4B2E2E]">
        “Namibekery is a local artisan bakery, meaning of ‘EVERY SLICE MATTERS’. We first opened our doors in 2020,
        and now we proudly serve in multiple cities across Indonesia. Namibekery is dedicated to crafting fresh,
        delicious baked goods daily using only premium, natural ingredients. Each creation is made with love,
        passion, and a touch of home.”
    </p>
    <div class="italic text-[#4B2E2E] mt-8 text-xl">We Are Namibekery</div>
</section>

{{-- Our Story Section --}}
<section class="bg-[#EAC6C1] text-white text-center py-16 px-6">
    <h2 class="text-3xl font-logo mb-3">Our Story</h2>
    <h3 class="text-xl font-semibold">
        A HOMEGROWN BAKERY SERVING A HEARTFELT SELECTION OF FRESHLY BAKED GOODS
    </h3>
</section>

{{-- Store Image --}}
<section class="py-12 px-6">
    <img src="{{ asset('EMPINK KUE.jpg') }}" alt="Namibekery Store"
         class="w-full max-w-4xl mx-auto shadow-lg rounded-lg">
</section>

{{-- Healthy Baked Goods Section (Seperti TOUS les JOURS) --}}
<section class="py-16 px-6 bg-white grid md:grid-cols-2 gap-8 items-center">
    <img src="{{ asset('ROTI.jpg') }}" alt="Kneading Dough"
         class="w-full max-w-md mx-auto shadow-md rounded-md">
    
    <div>
        <h3 class="text-2xl text-[#E18564] font-semibold italic mb-2">Healthy baked goods</h3>
        <h4 class="text-lg text-green-800 italic mb-4">with the purest and finest ingredients</h4>
        <p class="text-gray-700 leading-relaxed">
            We offer a wide variety of artisan baked goods—from breads, cakes, and cookies to special seasonal items.
            Each product is thoughtfully prepared using only the best ingredients. At Namibekery, we believe that 
            every slice matters and that freshness is the key to quality. That’s why our products are baked fresh daily
            just for you.
        </p>
    </div>
</section>

{{-- Global Section --}}
<section class="py-16 px-6 bg-[#FDF5EF] grid md:grid-cols-2 gap-8 items-center">
    <div>
        <h4 class="text-green-900 italic text-lg mb-1">We are local Indonesian Bakery with a global vision</h4>
        <h3 class="text-2xl font-semibold text-[#E18564] mb-4">NAMIBEKERY</h3>
        <p class="text-gray-700 leading-relaxed">
            Founded in 2020, Namibekery has grown from a small family-owned store into a growing brand with loyal 
            customers across cities in Indonesia. We aim to expand our reach while staying true to our core values: 
            using premium ingredients and baking with love.
        </p>
        <p class="mt-4 text-[#E18564] font-medium">Promise to bring you joy in every bite</p>
    </div>

    <img src="{{ asset('CHUOX.jpg') }}" alt="Ingredients and Bread"
         class="w-[300PX] max-w-md mx-auto shadow-md rounded-md">
</section>

@endsection
