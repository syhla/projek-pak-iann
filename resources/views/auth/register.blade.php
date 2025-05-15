@extends('layouts.app')

@section('content')
<head>
    <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap" rel="stylesheet">
        <style>
             .font-title {
    font-family: 'Playfair Display', serif;
  }
          </style>
</head>

<!-- Form Wrapper dengan Gradien Latar Belakang Dominan Pink -->
<div class="flex items-center justify-center  py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-6 border border-[#D4AF37]">
    <h2 class="text-3xl font-bold mb-6 text-center text-[#D4AF37] font-title">Register</h2>

    <!-- Form Register -->
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Input Nama -->
        <div class="mb-6">
            <label for="name" class="block mb-1 text-[#4B2E2E] font-medium">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required class="w-full border border-[#D4AF37] px-3 py-2 rounded-md focus:ring-2 focus:ring-[#d1ac33] transition duration-300 @error('name') border-red-500 @enderror">
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Input Email -->
        <div class="mb-6">
            <label for="email" class="block mb-1 text-[#4B2E2E] font-medium">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full border border-[#D4AF37] px-3 py-2 rounded-md focus:ring-2 focus:ring-[#d1ac33] transition duration-300 @error('email') border-red-500 @enderror">
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Input Password -->
        <div class="mb-6">
            <label for="password" class="block mb-1 text-[#4B2E2E] font-medium">Password</label>
            <input id="password" type="password" name="password" required class="w-full border border-[#D4AF37] px-3 py-2 rounded-md focus:ring-2 focus:ring-[#d1ac33] transition duration-300 @error('password') border-red-500 @enderror">
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Input Konfirmasi Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block mb-1 text-[#4B2E2E] font-medium">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full border border-[#D4AF37] px-3 py-2 rounded-md focus:ring-2 focus:ring-[#d1ac33] transition duration-300">
        </div>
        

        <!-- Tombol Submit -->
        <button type="submit" class="w-full bg-[#D4AF37] hover:bg-[#c1a032] text-white py-2 rounded-md font-semibold transition duration-300 ease-in-out transform hover:scale-105">
            Register
        </button>
    </form>

    </div>
</div>
@endsection
