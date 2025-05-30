<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomCakeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomCakeController extends Controller
{
    public function create()
    {
        return view('custom_cake'); // Sesuaikan dengan nama file blade kamu
    }

    public function store(CustomCakeRequest $request)
    {
        // Simpan data ke database (buat model dan migration sendiri ya kalau belum ada)
        // Contoh kalau belum ada, bisa simpan ke file log atau email dulu.

        $data = $request->validated();

        // Upload gambar kalau ada
        if ($request->hasFile('gambar_referensi')) {
            $path = $request->file('gambar_referensi')->store('custom_cake_images', 'public');
            $data['gambar_referensi'] = $path;
        } else {
            $data['gambar_referensi'] = null;
        }

        // Contoh: Simpan ke DB (buat model CustomCake dulu)
        // CustomCake::create($data);

        // Contoh sementara simpan ke log file (hanya demo)
        \Log::info('Pesanan Custom Cake:', $data);

        return redirect()->route('custom.create')->with('success', 'Pesanan custom kue kamu berhasil dikirim! Terima kasih.');
    }
}
