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
            ->orderBy('name')
            ->get();

        $finishedGames = Game::finished()->with(['homeTeam', 'awayTeam'])->get();

        $predictions = Prediction::whereIn('user_id', $users->pluck('id'))
            ->whereNotNull('points_earned')
            ->get()
            ->groupBy('user_id');

        return view('user.leaderboard', compact('users', 'finishedGames', 'predictions'));
    }
}
