<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Display all chat sessions for the admin.
     */
    public function index(): View
    {
        $sessions = ChatSession::withCount('messages')
            ->with(['messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->paginate(20);

        return view('admin.chat.index', compact('sessions'));
    }

    /**
     * Show a specific chat session with all messages.
     */
    public function show(ChatSession $chatSession): View
    {
        $chatSession->load(['messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        $sessions = ChatSession::withCount('messages')
            ->with(['messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        return view('admin.chat.show', compact('chatSession', 'sessions'));
    }

    /**
     * Send a reply from admin.
     */
    public function reply(Request $request, ChatSession $chatSession): RedirectResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $chatSession->messages()->create([
            'message' => $request->input('message'),
            'is_admin' => true,
            'user_id' => auth()->id(),
        ]);

        $chatSession->touch();

        return redirect()
            ->route('admin.chat.show', $chatSession)
            ->with('success', 'Pesan berhasil dikirim.');
    }
}

