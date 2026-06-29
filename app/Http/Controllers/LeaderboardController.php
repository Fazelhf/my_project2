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
        $users = User::regular()->orderBy('name')->get();

        $finishedGames = Game::finished()->with(['homeTeam', 'awayTeam'])->get();

        $predictions = Prediction::whereIn('user_id', $users->pluck('id'))
            ->whereNotNull('points_earned')
            ->get()
            ->groupBy('user_id');

        // Compute live score from predictions (avoids stale total_score)
        $users->each(function (User $user) use ($predictions) {
            $user->live_score = $predictions->get($user->id, collect())->sum(function ($p) {
                return $p->points_override ?? $p->points_earned ?? 0;
            });
        });

        $users = $users->sortByDesc('live_score')->sortBy(fn ($u) => $u->live_score === $users->max('live_score') ? 0 : $u->name)->values();

        // Re-sort: descending live_score, then name ascending for ties
        $users = $users->sortBy([
            ['live_score', 'desc'],
            ['name', 'asc'],
        ])->values();

        return view('user.leaderboard', compact('users', 'finishedGames', 'predictions'));
    }
}
