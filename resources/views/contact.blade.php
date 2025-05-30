@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-6xl px-4 py-10">
  {{-- Form dan Kontak untuk guest dan customer --}}
  @if(auth()->guest() || (auth()->check() && auth()->user()->hasRole('customer')))
  <div class="bg-[#f0ecd4] rounded-3xl shadow-xl p-10 mb-11">
    <h2 class="text-3xl font-bold text-[#556B2F] mb-8">Hubungi Kami</h2>
    <div class="grid md:grid-cols-2 gap-8">
        {{-- Bagian Kontak --}}
        <div class="space-y-5 text-gray-700 text-sm">
          {{-- Hotline --}}
          <div class="flex gap-3 items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#556B2F] mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 14v4a2 2 0 01-2 2h-2.5m-5 0H6a2 2 0 01-2-2v-4m16-2V9a7 7 0 10-14 0v3a2 2 0 002 2h10a2 2 0 002-2z"/>
            </svg>
            <div>
              <p class="font-semibold">Hotline</p>
              <p class="text-lg font-bold text-gray-800">1500581</p>
            </div>
          </div>

          {{-- WhatsApp --}}
          <div class="flex gap-3 items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#D4AF37] mt-1" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20.52 3.48A11.87 11.87 0 0012 0C5.37 0 0 5.37 0 12a11.9 11.9 0 001.61 6L0 24l6.24-1.64A11.87 11.87 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.19-3.48-8.52zM12 22c-1.9 0-3.76-.5-5.4-1.44l-.39-.23-3.7.98.99-3.6-.25-.38A9.91 9.91 0 012 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.1-7.6c-.27-.14-1.6-.78-1.85-.87-.25-.1-.43-.14-.6.14-.18.27-.7.87-.86 1.04-.16.17-.32.2-.6.07-.27-.14-1.16-.43-2.2-1.38-.81-.72-1.36-1.6-1.5-1.87-.15-.27-.02-.42.11-.56.12-.12.27-.32.4-.48.13-.17.17-.28.25-.47.08-.17.04-.35-.02-.5-.07-.14-.6-1.44-.82-1.98-.22-.54-.44-.47-.6-.47H7.4c-.17 0-.45.07-.68.35-.23.27-.9.88-.9 2.13 0 1.25.9 2.46 1.02 2.64.12.17 1.76 2.7 4.28 3.78.6.26 1.07.41 1.43.52.6.19 1.14.16 1.57.1.48-.07 1.6-.65 1.83-1.28.22-.6.22-1.12.16-1.28-.06-.16-.23-.25-.5-.4z"/>
            </svg>
            <div>
              <p class="font-semibold">WhatsApp</p>
              <p>+62 812 1314 1500</p>
            </div>
          </div>

          {{-- Email --}}
          <div class="flex gap-3 items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#556B2F] mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12l-4 4m0 0l-4-4m4 4V8m8-4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z"/>
            </svg>
            <div>
              <p class="font-semibold">Email</p>
              <p>namibakery@gmail.com</p>
            </div>
          </div>

          {{-- Alamat --}}
          <div class="flex gap-3 items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#556B2F] mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 10s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z"/>
            </svg>
            <div>
              <p class="font-semibold">Alamat</p>
              <p>Jl. Contoh Alamat No.123, Jakarta, Indonesia</p>
            </div>
          </div>

          {{-- Jam Operasional --}}
          <div class="flex gap-3 items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#556B2F] mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
              <p class="font-semibold">Jam Operasional</p>
              <p>Senin – Minggu, 08.00 – 21.00 WIB</p>
            </div>
          </div>
        </div>

      {{-- Form Komentar --}}
      <div>
        <h2 class="text-2xl font-bold text-[#556B2F] mb-8">Tinggalkan Komentar</h2>

        @if(session('success'))
          <p class="text-green-700 mb-3">{{ session('success') }}</p>
        @endif

        @guest
          <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md shadow mb-4">
            <p class="text-sm">
              <strong>Ingin berkomentar?</strong><br>
              <a href="{{ route('login') }}" class="underline font-semibold text-yellow-800 hover:text-yellow-900">Login</a> atau 
              <a href="{{ route('register') }}" class="underline font-semibold text-yellow-800 hover:text-yellow-900">Register</a> terlebih dahulu.
            </p>
          </div>
        @endguest

        @auth
          @if(auth()->user()->hasRole('customer'))
            <form action="{{ route('customer.comments.store') }}" method="POST">
              @csrf
              <label for="pesan" class="block text-base text-gray-700 mb-4">Komentar Anda sangat berharga bagi kami</label>
              <textarea name="pesan" id="pesan" rows="4" placeholder="Tulis komentar di sini..." class="w-full px-4 py-3 text-gray-800 placeholder-gray-400 border border-[#556B2F]/40 rounded-md focus:ring-2 focus:ring-[#556B2F] focus:border-[#556B2F] transition duration-200 ease-in-out resize-none shadow-sm">{{ old('pesan') }}</textarea>
              @error('pesan')
                <p class="text-red-500 text-sm italic">{{ $message }}</p>
              @enderror
              <button type="submit" class="bg-[#556B2F] hover:bg-[#4a5a22] text-white font-medium px-6 py-2 rounded-md transition duration-300 ease-in-out">Kirim Komentar</button>
            </form>
          @endif
        @endauth
      </div>
    </div>
  </div>
  @endif

  {{-- Daftar komentar untuk admin atau role selain customer --}}
  @if(auth()->check() && !auth()->user()->hasRole('customer'))
  <div x-data="{ filter: 'approved' }" class="p-8 mx-auto">
    <h2 class="text-3xl font-bold text-[#556B2F] mb-6 text-center">Komentar Pengunjung</h2>

    <div class="relative mb-8 w-full md:w-1/3 mx-auto">
      <label for="filter" class="block mb-2 text-sm font-semibold text-gray-700">Filter Status Komentar</label>
      <select id="filter" x-model="filter" class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#556B2F] focus:border-[#556B2F] bg-white appearance-none cursor-pointer">
        <option value="approved">Disetujui</option>
        <option value="rejected">Ditolak</option>
        <option value="all">Semua</option>
      </select>
    </div>

    <div class="space-y-6 max-h-[380px] overflow-y-auto pr-4 custom-scroll">
      <template x-if="filter === 'approved'">
        <div>
          @forelse($approvedComments as $comment)
            <div class="border-l-4 border-[#33ac28] bg-[#d9f9d6] rounded-md p-5 shadow-md mb-4">
              <p class="text-[#33ac28] font-semibold mb-3">✔ Komentar Disetujui</p>
              <p class="text-gray-800 text-sm leading-relaxed">{{ $comment->pesan }}</p>
              <p class="text-xs text-gray-500 mt-3">{{ $comment->created_at->format('d M Y, H:i') }}</p>
            </div>
          @empty
            <p class="text-gray-500 italic">Belum ada komentar yang disetujui.</p>
          @endforelse
        </div>
      </template>

      <template x-if="filter === 'rejected'">
        <div>
          @forelse($rejectedComments as $comment)
            <div class="border-l-4 border-red-500 bg-red-50 rounded-md p-5 shadow-md mb-4">
              <p class="text-red-700 font-semibold mb-3">✖ Komentar Ditolak</p>
              <p class="text-gray-800 text-sm leading-relaxed">{{ $comment->pesan }}</p>
              <p class="text-xs text-gray-500 mt-3">{{ $comment->created_at->format('d M Y, H:i') }}</p>
            </div>
          @empty
            <p class="text-gray-500 italic">Belum ada komentar yang ditolak.</p>
          @endforelse
        </div>
      </template>

      <template x-if="filter === 'all'">
        <div>
          @forelse($comments as $comment)
            <div class="border-l-4 rounded-md p-5 shadow-md mb-4
              @if($comment->status === 'approved') border-[#D4AF37] bg-[#fdf8e2] text-[#D4AF37]
              @elseif($comment->status === 'rejected') border-red-500 bg-red-50 text-red-700
              @else border-gray-300 bg-gray-50 text-gray-700 @endif">
              <p class="font-semibold mb-3">
                @if($comment->status === 'approved') ✔ Komentar Disetujui
                @elseif($comment->status === 'rejected') ✖ Komentar Ditolak
                @else Semua Komentar
                @endif
              </p>
              <p class="text-gray-800 text-sm leading-relaxed">{{ $comment->pesan }}</p>
              <p class="text-xs text-gray-500 mt-3">{{ $comment->created_at->format('d M Y, H:i') }}</p>
            </div>
          @empty
            <p class="text-gray-500 italic">Belum ada komentar.</p>
          @endforelse
        </div>
      </template>
    </div>
  </div>
  @endif

  {{-- Google Maps untuk tamu dan pengunjung --}}
  @guest
    <div class="mt-16 rounded-xl shadow-xl overflow-hidden">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126915.04866170468!2d106.68943262506666!3d-6.229386897788109!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f19bf4d6dfdb%3A0x7235f7b4a6a15453!2sBandung%2C%20West%20Java%2C%20Indonesia!5e0!3m2!1sen!2sid!4v1685429307520!5m2!1sen!2sid"
        width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  @endguest

  @auth
    @if(auth()->user()->hasRole('customer'))
      <div class="mt-16 rounded-xl shadow-xl overflow-hidden">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126915.04866170468!2d106.68943262506666!3d-6.229386897788109!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f19bf4d6dfdb%3A0x7235f7b4a6a15453!2sBandung%2C%20West%20Java%2C%20Indonesia!5e0!3m2!1sen!2sid!4v1685429307520!5m2!1sen!2sid"
          width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    @endif
  @endauth

</div> {{-- END container --}}
@endsection
