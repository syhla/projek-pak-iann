<?php
// app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Tambahkan ini untuk mengimpor Auth
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Proses pendaftaran pengguna baru
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    // Validasi inputan register
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    // Buat pengguna baru
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'customer', // Default role customer
    ]);

    // Jangan langsung login, redirect ke login page
    return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
}
}
