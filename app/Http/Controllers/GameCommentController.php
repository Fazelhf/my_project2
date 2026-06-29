<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameComment;
use App\Models\GameCommentLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GameCommentController extends Controller
{
    public function store(Request $request, Game $game): RedirectResponse
    {
        $request->validate([
            'body'      => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'exists:game_comments,id'],
        ], [
            'body.required' => 'متن نظر نمی‌تواند خالی باشد.',
            'body.max'      => 'نظر حداکثر ۱۰۰۰ کاراکتر می‌تواند داشته باشد.',
        ]);

        $parentId = $request->parent_id;
        if ($parentId) {
            $parent = GameComment::find($parentId);
            if (!$parent || $parent->game_id !== $game->id) {
                $parentId = null;
            }
        }

        GameComment::create([
            'game_id'   => $game->id,
            'user_id'   => auth()->id(),
            'parent_id' => $parentId,
            'body'      => strip_tags($request->body),
        ]);

        return back()->with('success', 'نظر شما ثبت شد.');
    }

    public function like(Request $request, GameComment $comment): JsonResponse
    {
        $userId = auth()->id();
        $existing = GameCommentLike::where('comment_id', $comment->id)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            GameCommentLike::create(['comment_id' => $comment->id, 'user_id' => $userId]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $comment->likes()->count(),
        ]);
    }

    public function destroy(GameComment $comment): RedirectResponse
    {
        $user = auth()->user();
        if ($comment->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        $comment->update(['is_deleted' => true]);
        return back()->with('success', 'نظر حذف شد.');
    }
}
