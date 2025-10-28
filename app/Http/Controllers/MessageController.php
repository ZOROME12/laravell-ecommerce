<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function fetch($userId)
    {
        return Message::with(['sender', 'receiver']) // 👈 This line was already present
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function send(Request $request)
    {
        // Basic validation (consider adding more specific rules)
        $request->validate([
            'sender_id' => 'required|integer',
            'receiver_id' => 'required|integer',
            'message' => 'required|string',
            'is_admin' => 'required|boolean',
        ]);

        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_admin' => $request->is_admin,
            'read' => false, // Ensure 'read' defaults to false
        ]);

        // Eager load sender/receiver if needed for the response
        $message->load(['sender', 'receiver']);

        return response()->json(['message' => $message]);
    }

    public function markAsRead($userId)
    {
        // Mark messages *sent to* the user *by an admin* as read
        Message::where('receiver_id', $userId)
            ->where('is_admin', true) // Only mark admin messages as read by the user
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['status' => 'read']);
    }

    // ------------------ MOBILE APP METHODS (NEW) ------------------ //
    public function fetchMobile(Request $request)
    {
        // Consider adding validation for user_id existence if needed
        $user_id = $request->query('user_id');

        if (!$user_id) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        // Fetch messages, potentially eager load sender/receiver if needed by mobile app
        $messages = Message::with(['sender', 'receiver']) // Added eager loading
            ->where(function ($query) use ($user_id) {
                $query->where('sender_id', $user_id)
                      ->orWhere('receiver_id', $user_id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function storeMobile(Request $request)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'sender_id'   => 'required|integer|exists:users,id', // Ensure sender exists
                'receiver_id' => 'required|integer', // Usually 0 for admin, validate if needed
                'message'     => 'required|string|max:1000', // Add max length
                'is_admin'    => 'boolean', // is_admin should likely NOT be sent by mobile, set server-side?
            ]);

            // Determine if the sender is an admin based on the sender_id or authentication
            // For simplicity, assuming mobile app sends 'is_admin' correctly for now,
            // but ideally, this logic should be server-side based on authentication.
            $isAdminSender = $request->is_admin ?? false; // Default to false if not provided

            $message = Message::create([
                'sender_id'   => $request->sender_id,
                'receiver_id' => $request->receiver_id, // Ensure this is correctly set (e.g., 0 for admin)
                'message'     => $request->message,
                'is_admin'    => $isAdminSender, // Use determined value
                'read'        => false, // New messages are unread
            ]);

            // Eager load sender/receiver for the response data
            $message->load(['sender', 'receiver']);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully.',
                'data'    => $message,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
             // Return validation errors
             return response()->json([
                 'success' => false,
                 'message' => 'Validation failed.',
                 'errors'  => $e->errors(),
             ], 422); // Use 422 for validation errors
        } catch (\Exception $e) {
             // Log the error for debugging
             \Log::error('Mobile message store failed: '.$e->getMessage());

             // Return a generic server error response
             return response()->json([
                 'success' => false,
                 'message' => 'Failed to send message due to a server error.',
                 // 'error'   => $e->getMessage(), // Optionally include error in dev environments
             ], 500);
        }
    }
    // ---------------- END MOBILE APP METHODS ---------------- //

} // End of Class
