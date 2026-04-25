<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of messages (inbox).
     */
    /**
     * Display a listing of messages grouped by user (conversation list).
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get unique users who have had conversations with the current user
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function($message) use ($user) {
                return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->map(function($group) {
                return $group->first(); // Get the latest message for each user
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => $conversations
        ]);
    }

    /**
     * Get chat history between current user and another user.
     */
    public function getChatHistory(Request $request, $userId)
    {
        $currentUser = $request->user();
        
        $messages = Message::where(function($query) use ($currentUser, $userId) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($currentUser, $userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $currentUser->id);
            })
            ->with(['sender', 'receiver'])
            ->oldest()
            ->get();

        // Mark messages from other user as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'is_read' => false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver'])
        ], 201);
    }
}
