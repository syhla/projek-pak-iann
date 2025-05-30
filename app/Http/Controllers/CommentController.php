<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only(['index', 'approve', 'reject']);
        $this->middleware('role:customer')->only(['store']);
    }

    // Tampilkan semua komentar di admin panel dengan data terpisah berdasarkan status
    public function index()
    {
        $approvedComments = Comment::where('status', 'approved')->latest()->get();
        $rejectedComments = Comment::where('status', 'rejected')->latest()->get();
        $comments = Comment::latest()->get(); // semua komentar

        return view('admin.comments.index', compact('approvedComments', 'rejectedComments', 'comments'));
    }

    // Approve komentar
    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Komentar disetujui.');
    }

    // Reject komentar
    public function reject(Comment $comment)
    {
        $comment->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Komentar ditolak.');
    }

    // Simpan komentar dari customer
    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string|max:500',
        ]);

        $request->user()->comments()->create([
            'pesan' => $request->pesan,
            'status' => 'pending', // status menunggu persetujuan
        ]);

        return redirect()->route('welcome')->with('success', 'Komentar berhasil dikirim dan menunggu persetujuan.');
    }
}
