<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter') === 'unread' ? 'unread' : 'all';

        $query = ContactMessage::latest();

        if ($filter === 'unread') {
            $query->where('is_read', false);
        }

        $messages = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
        ];

        return view('admin.messages.index', compact('messages', 'stats', 'filter'));
    }

    /**
     * Dipanggil otomatis lewat fetch() saat pesan dibuka (balas JSON),
     * tetapi tetap aman dipakai sebagai form biasa (balas redirect).
     */
    public function markRead(Request $request, ContactMessage $message): JsonResponse|RedirectResponse
    {
        $message->update(['is_read' => true]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    public function markUnread(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => false]);

        return back()->with('success', 'Pesan ditandai belum dibaca.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
