<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();
        return view('chat.index', compact('users'));
    }

    public function fetchMessages($userId)
    {
        $authId = auth()->id();
        $messages = \App\Models\Message::where(function($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })
            ->orWhere(function($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        \App\Models\Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:51200', // 50MB max
        ]);

        if (!$request->message && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message or attachment is required'], 400);
        }

        $message = new \App\Models\Message();
        $message->sender_id = auth()->id();
        $message->receiver_id = $request->receiver_id;
        $message->message = $request->message;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat_attachments', 'public');
            $message->attachment_path = $path;
            
            // Determine type
            $mime = $file->getMimeType();
            if (str_starts_with($mime, 'image/')) {
                $message->attachment_type = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $message->attachment_type = 'video';
            } elseif (str_starts_with($mime, 'audio/')) {
                $message->attachment_type = 'audio';
            } else {
                $message->attachment_type = 'document';
            }
        }

        $message->save();

        return response()->json($message);
    }
}
