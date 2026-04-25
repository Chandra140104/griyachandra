<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'user') {
            abort(403, 'Akses ditolak. Fitur Inbox hanya untuk penyewa.');
        }

        $messages = Message::where('receiver_id', auth()->id())
            ->with('sender')
            ->latest()
            ->paginate(10);

        return view('inbox.index', compact('messages'));
    }

    public function show(Message $message)
    {
        if (auth()->user()->role !== 'user') {
            abort(403, 'Akses ditolak. Fitur Inbox hanya untuk penyewa.');
        }

        // Ensure the user is the receiver
        if ($message->receiver_id !== auth()->id()) {
            abort(403);
        }

        // Mark as read
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('inbox.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        if (auth()->user()->role !== 'user') {
            abort(403, 'Akses ditolak. Fitur Inbox hanya untuk penyewa.');
        }

        if ($message->receiver_id !== auth()->id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('inbox.index')->with('success', 'Message deleted.');
    }

    public function previewAttachment(Message $message)
    {
        // Ensure the user is the receiver or admin
        if ($message->receiver_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        if (!$message->attachment) {
            abort(404);
        }

        $path = storage_path('app/public/' . $message->attachment);

        if (!file_exists($path)) {
            // Try secondary path if not found (just in case)
            $path = public_path('storage/' . $message->attachment);
            if (!file_exists($path)) {
                abort(404, 'File tidak ditemukan di storage.');
            }
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
