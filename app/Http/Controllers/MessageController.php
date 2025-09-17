<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function fetch($userId)
    {
        return Message::with(['sender', 'receiver']) // 👈 add this line
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_admin' => $request->is_admin,
        ]);

        return response()->json(['message' => $message]);
    }

    public function markAsRead($userId)
    {
        Message::where('receiver_id', $userId)
            ->where('is_admin', true)
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['status' => 'read']);
    }
}
