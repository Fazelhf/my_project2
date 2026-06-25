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
        $users = User::regular()
            ->orderByDesc('total_score')
            ->orderByDesc('id')
            ->get();

        $finishedGames = Game::with(['homeTeam', 'awayTeam'])
            ->finished()
            ->orderBy('scheduled_at')
            ->get();

        // predictions keyed by [user_id][game_id]
        $predictions = Prediction::whereIn('game_id', $finishedGames->pluck('id'))
            ->get()
            ->groupBy('user_id');

        return view('user.leaderboard', compact('users', 'finishedGames', 'predictions'));
    }
}
