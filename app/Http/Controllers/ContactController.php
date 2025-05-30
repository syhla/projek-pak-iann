<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class ContactController extends Controller
{
public function index()
{
    if (auth()->check() && auth()->user()->role === 'admin') {
        // Admin lihat semua komentar
        $approvedComments = Comment::with('user')->where('status', 'approved')->latest()->get();
        $rejectedComments = Comment::with('user')->where('status', 'rejected')->latest()->get();
        $comments = Comment::with('user')->latest()->get();
    } else {
        // Pengunjung lihat hanya Disetujui & Ditolak
        $approvedComments = Comment::with('user')->where('status', 'approved')->latest()->get();
        $rejectedComments = Comment::with('user')->where('status', 'rejected')->latest()->get();
        $comments = $approvedComments->merge($rejectedComments)->sortByDesc('created_at');
    }

    return view('contact', compact('comments', 'approvedComments', 'rejectedComments'));
}
}