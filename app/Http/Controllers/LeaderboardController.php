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
            ->get()
            ->groupBy('user_id');

        $users->each(function (User $user) use ($predictions) {
            $user->live_score = $predictions->get($user->id, collect())->sum(function ($p) {
                return $p->points_override ?? $p->points_earned ?? 0;
            });
        });

        $users = $users->sort(function ($a, $b) {
            if ($b->live_score !== $a->live_score) {
                return $b->live_score - $a->live_score;
            }
            return strcmp($a->name, $b->name);
        })->values();

        return view('user.leaderboard', compact('users', 'finishedGames', 'predictions'));
    }
}
