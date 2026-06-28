<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class LeaderboardController extends Controller
{
    public function index(): Response
    {
        $users = User::regular()
            ->orderByDesc('total_score')
            ->orderByDesc('id')
            ->get();

        $finishedGames = Game::with(['homeTeam', 'awayTeam'])
            ->finished()
            ->orderBy('scheduled_at')
            ->get();

        $predictions = Prediction::whereIn('game_id', $finishedGames->pluck('id'))
            ->get()
            ->groupBy('user_id')
            ->map(fn ($preds) => $preds->keyBy('game_id')->map(fn ($p) => [
                'home_score'    => $p->home_score,
                'away_score'    => $p->away_score,
                'points_earned' => $p->points_earned,
            ]));

        return Inertia::render('Leaderboard', [
            'users' => $users->map(fn ($u) => [
                'id'          => $u->id,
                'name'        => $u->name,
                'department'  => $u->department,
                'total_score' => $u->total_score,
            ]),
            'finishedGames' => $finishedGames->map(fn ($g) => [
                'id'         => $g->id,
                'home_code'  => $g->homeTeam->code,
                'away_code'  => $g->awayTeam->code,
                'home_name'  => $g->homeTeam->name,
                'away_name'  => $g->awayTeam->name,
                'home_score' => $g->home_score,
                'away_score' => $g->away_score,
            ]),
            'predictions' => $predictions,
        ]);
    }
}
