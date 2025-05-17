<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'pesan' => 'required|string|max:1000',
        ]);

        Comment::create([
            'nama' => $request->nama,
            'pesan' => $request->pesan,
        ]);

        return redirect()->route('contact')->with('success', 'Komentar berhasil dikirim!');
    }
}