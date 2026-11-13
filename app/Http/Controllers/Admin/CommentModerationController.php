<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentModerationController extends Controller
{
    public function index(): View
    {
        $pending = NewsComment::with('news')->where('status', 'pending')->latest()->get();

        $stats = [
            'pending' => NewsComment::where('status', 'pending')->count(),
            'approved' => NewsComment::where('status', 'approved')->count(),
            'rejected' => NewsComment::where('status', 'rejected')->count(),
        ];

        return view('admin.comments.index', compact('pending', 'stats'));
    }

    public function approve(NewsComment $comment): RedirectResponse
    {
        $comment->update(['status' => 'approved']);

        return back()->with('success', 'Komentar disetujui.');
    }

    public function reject(NewsComment $comment): RedirectResponse
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('success', 'Komentar ditolak.');
    }
}
