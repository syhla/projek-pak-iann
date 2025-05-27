<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Middleware cek login dan role admin
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only(['index', 'approve', 'reject']);
    }

    // Tampilkan semua komentar di admin panel
 public function index()
    {
        $comments = Comment::with('user')->latest()->get();

        return view('admin.comments.index', compact('comments'));
    }

    // Approve komentar
    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'Disetujui']);
        return redirect()->back()->with('success', 'Komentar disetujui.');
    }

    // Reject komentar
    public function reject(Comment $comment)
    {
        $comment->update(['status' => 'Ditolak']);
        return redirect()->back()->with('success', 'Komentar ditolak.');
    }

    // Simpan komentar dari customer (middleware role:customer)
    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string|max:500',
        ]);

        $request->user()->comments()->create([
            'pesan' => $request->pesan,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('welcome')->with('success', 'Komentar berhasil dikirim dan menunggu persetujuan.');
        }
}
