<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(): View
    {
        $users = User::orderByDesc('total_score')
            ->orderByDesc('id')
            ->get();

        // For head-to-head: finished games with predictions from both users
        $finishedGames = Game::with(['homeTeam', 'awayTeam'])
            ->where('status', 'finished')
            ->orderBy('scheduled_at')
            ->get();

        // All predictions keyed by [user_id][game_id]
        $predictions = Prediction::whereIn('game_id', $finishedGames->pluck('id'))
            ->get()
            ->groupBy('user_id')
            ->map(fn($preds) => $preds->keyBy('game_id'));

        return view('user.leaderboard', compact('users', 'finishedGames', 'predictions'));
    }
}
