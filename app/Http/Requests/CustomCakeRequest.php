<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomCakeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Atur sesuai kebutuhan, misal hanya user login
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'no_wa' => ['required', 'regex:/^[0-9]+$/', 'max:15'],
            'desain' => 'required|string|max:1000',
            'gambar_referensi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ];
    }

    public function messages()
    {
        return [
            'no_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'gambar_referensi.image' => 'File harus berupa gambar.',
            'gambar_referensi.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar_referensi.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
