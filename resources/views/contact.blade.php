@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-6xl px-4 py-12">
    <div class="bg-[#FFF0F2] rounded-3xl shadow-xl p-10">
        <div class="grid md:grid-cols-2 gap-10">
            
            <!-- Kontak Info -->
            <div class="space-y-6">
                <h2 class="text-3xl font-bold text-[#D77A61] tracking-wide mb-4">Hubungi Kami</h2>

                <div class="space-y-5 text-gray-700 text-sm">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-headset text-2xl text-[#D77A61] mt-1"></i>
                        <div>
                            <p class="font-semibold">Hotline</p>
                            <p class="text-lg font-bold text-gray-800">1500581</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <i class="fab fa-whatsapp text-2xl text-[#25D366] mt-1"></i>
                        <div>
                            <p class="font-semibold">WhatsApp</p>
                            <p>+62812 1314 1500</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <i class="fas fa-envelope text-2xl text-[#D77A61] mt-1"></i>
                        <div>
                            <p class="font-semibold">Email</p>
                            <p>marketing@harvestcakes.com</p>
                            <p>corporate@harvestcakes.com</p>
                        </div>
                    </div>

                    <hr class="border-gray-300 my-6">

                    <div>
                        <p class="font-semibold text-gray-800 mb-2">Find Us On</p>
                        <div class="flex flex-wrap gap-4">
                             <!-- Instagram -->
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow hover:text-pink-600 transition overflow-hidden flex-shrink-0" aria-label="Instagram">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 2 .3 2.5.5.6.3 1 .6 1.5 1.2.5.5.9.9 1.2 1.5.2.5.4 1.3.5 2.5.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.3 2-.5 2.5-.3.6-.6 1-1.2 1.5-.5.5-.9.9-1.5 1.2-.5.2-1.3.4-2.5.5-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-2-.3-2.5-.5-.6-.3-1-.6-1.5-1.2-.5-.5-.9-.9-1.2-1.5-.2-.5-.4-1.3-.5-2.5C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.3-2 .5-2.5.3-.6.6-1 1.2-1.5.5-.5.9-.9 1.5-1.2.5-.2 1.3-.4 2.5-.5C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.1-1 .1-1.6.2-2 .4-.5.2-.8.4-1.2.8-.4.4-.6.7-.8 1.2-.2.4-.3 1-.4 2-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1 .2 1.6.4 2 .2.5.4.8.8 1.2.4.4.7.6 1.2.8.4.2 1 .3 2 .4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1-.1 1.6-.2 2-.4.5-.2.8-.4 1.2-.8.4-.4.6-.7.8-1.2.2-.4.3-1 .4-2 .1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1-.2-1.6-.4-2-.2-.5-.4-.8-.8-1.2-.4-.4-.7-.6-1.2-.8-.4-.2-1-.3-2-.4-1.2-.1-1.6-.1-4.7-.1zm0 3.8a5.2 5.2 0 110 10.4 5.2 5.2 0 010-10.4zm0 1.8a3.4 3.4 0 100 6.8 3.4 3.4 0 000-6.8zm4.6-2.2a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/>
                             </svg>
                            </a>

                              <!-- Facebook -->
    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow hover:text-blue-600 transition overflow-hidden flex-shrink-0" aria-label="Facebook">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.098 2.795.142v3.24h-1.918c-1.504 0-1.796.715-1.796 1.763v2.31h3.59l-.467 3.622h-3.123V24h6.116c.73 0 1.324-.593 1.324-1.326V1.326C24 .593 23.407 0 22.675 0z"/>
        </svg>
    </a>

                            <!-- Twitter -->
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow hover:text-blue-400 transition" aria-label="Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M24 4.557a9.828 9.828 0 01-2.828.775 4.932 4.932 0 002.165-2.723 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.38 4.482A13.94 13.94 0 011.671 3.15a4.916 4.916 0 001.523 6.574 4.903 4.903 0 01-2.229-.616c-.054 2.281 1.581 4.415 3.949 4.89a4.93 4.93 0 01-2.224.084 4.919 4.919 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.396 0-.787-.023-1.175-.069a13.945 13.945 0 007.548 2.212c9.058 0 14.01-7.508 14.01-14.01 0-.213-.005-.425-.014-.636A10.012 10.012 0 0024 4.557z"/>
                                </svg>
                            </a>

                            <!-- YouTube -->
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow hover:text-red-600 transition" aria-label="YouTube">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M23.5 6.2c-.3-1.2-1.2-2.1-2.4-2.4C19.2 3.3 12 3.3 12 3.3s-7.2 0-9.1.5c-1.2.3-2.1 1.2-2.4 2.4C0 8.1 0 12 0 12s0 3.9.5 5.8c.3 1.2 1.2 2.1 2.4 2.4 1.9.5 9.1.5 9.1.5s7.2 0 9.1-.5c1.2-.3 2.1-1.2 2.4-2.4.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8zM9.6 15.5V8.5l6.4 3.5-6.4 3.5z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Komentar -->
            <div>
                <h2 class="text-3xl font-bold text-[#D77A61] mb-4">Tinggalkan Komentar</h2>
                <p class="text-sm text-gray-600 mb-6">Kami senang mendengar dari Anda 🍰</p>

                @if(session('success'))
                    <p class="text-green-600 mb-3">{{ session('success') }}</p>
                @endif

                <form action="{{ route('comments.store') }}" method="POST">
    @csrf

    <input 
        type="text" 
        name="nama" 
        placeholder="Nama Anda" 
        class="w-full p-3 mb-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D77A61]" 
        value="{{ old('nama') }}" 
        required
    >

    <textarea 
        name="pesan" 
        rows="5" 
        class="w-full p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D77A61]" 
        placeholder="Tulis komentar Anda..." 
        required>{{ old('pesan') }}</textarea>

    <button type="submit" class="mt-4 px-6 py-2 bg-[#D77A61] text-white font-semibold rounded-xl hover:bg-[#c05d47] transition-all">
        Kirim
    </button>
</form>

            </div>

        </div>
    </div>
</div>
@endsection
