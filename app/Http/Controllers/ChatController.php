<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $messages = ChatMessage::with('user')
            ->where('is_deleted', false)
            ->orderBy('created_at')
            ->take(100)
            ->get();

        return view('user.chat', compact('messages'));
    }

    public function messages(Request $request): JsonResponse
    {
        $since = $request->get('since', 0);
        $messages = ChatMessage::with('user')
            ->where('is_deleted', false)
            ->where('id', '>', $since)
            ->orderBy('created_at')
            ->take(50)
            ->get()
            ->map(fn($m) => [
                'id'         => $m->id,
                'body'       => $m->body,
                'user_id'    => $m->user_id,
                'user_name'  => $m->user->name,
                'username'   => $m->user->username ?? $m->user->name,
                'avatar'     => mb_strtoupper(mb_substr($m->user->name, 0, 1, 'UTF-8')),
                'created_at' => $m->created_at->diffForHumans(),
                'is_me'      => $m->user_id === auth()->id(),
            ]);

        return response()->json(['messages' => $messages]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ], [
            'body.required' => 'پیام نمی‌تواند خالی باشد.',
            'body.max'      => 'پیام حداکثر ۵۰۰ کاراکتر می‌تواند داشته باشد.',
        ]);

        $message = ChatMessage::create([
            'user_id' => auth()->id(),
            'body'    => strip_tags($request->body),
        ]);

        $message->load('user');

        return response()->json([
            'message' => [
                'id'         => $message->id,
                'body'       => $message->body,
                'user_id'    => $message->user_id,
                'user_name'  => $message->user->name,
                'username'   => $message->user->username ?? $message->user->name,
                'avatar'     => mb_strtoupper(mb_substr($message->user->name, 0, 1, 'UTF-8')),
                'created_at' => 'همین الان',
                'is_me'      => true,
            ],
        ]);
    }

    public function destroy(ChatMessage $message): JsonResponse
    {
        $user = auth()->user();
        if ($message->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        $message->update(['is_deleted' => true]);
        return response()->json(['ok' => true]);
    }
}
