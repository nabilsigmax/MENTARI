<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Get or create a chat session for the current visitor, then return messages.
     */
    public function messages(Request $request): JsonResponse
    {
        $session = $this->resolveSession($request);

        $messages = $session->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn (ChatMessage $msg) => [
                'id' => $msg->id,
                'message' => $msg->message,
                'is_admin' => $msg->is_admin,
                'created_at' => $msg->created_at->format('H:i'),
            ]);

        return response()->json([
            'session_id' => $session->id,
            'messages' => $messages,
        ]);
    }

    /**
     * Store a new message from the customer.
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'customer_name' => 'nullable|string|max:100',
        ]);

        $session = $this->resolveSession($request);

        // Update customer name if provided and not yet set
        if ($request->filled('customer_name') && ! $session->customer_name) {
            $session->update(['customer_name' => $request->input('customer_name')]);
        }

        $message = $session->messages()->create([
            'message' => $request->input('message'),
            'is_admin' => false,
            'user_id' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'is_admin' => false,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * Resolve or create a ChatSession for the current visitor using a session token.
     */
    private function resolveSession(Request $request): ChatSession
    {
        $sessionId = $request->session()->get('chat_session_id');

        if ($sessionId) {
            $session = ChatSession::find($sessionId);
            if ($session) {
                return $session;
            }
        }

        $session = ChatSession::create([
            'customer_name' => $request->input('customer_name', 'Pengunjung'),
        ]);

        $request->session()->put('chat_session_id', $session->id);

        return $session;
    }
}

