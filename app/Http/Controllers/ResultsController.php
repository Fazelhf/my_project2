<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;

class ResultsController extends Controller
{
    public function index(): Response
    {
        $games = Game::with(['homeTeam', 'awayTeam', 'predictions.user'])
            ->where('status', 'finished')
            ->orderByDesc('scheduled_at')
            ->get()
            ->map(fn ($g) => [
                'id'         => $g->id,
                'home_name'  => $g->homeTeam->name,
                'away_name'  => $g->awayTeam->name,
                'home_code'  => $g->homeTeam->code,
                'away_code'  => $g->awayTeam->code,
                'home_score' => $g->home_score,
                'away_score' => $g->away_score,
                'stage'      => $g->stage,
                'venue'      => $g->venue,
                'scheduled_at_formatted' => $g->scheduled_at->timezone('Asia/Tehran')->format('j M H:i'),
                'predictions' => $g->predictions->map(fn ($p) => [
                    'user_name'     => $p->user->name,
                    'home_score'    => $p->home_score,
                    'away_score'    => $p->away_score,
                    'points_earned' => $p->points_earned,
                ]),
            ]);

        return Inertia::render('Results', [
            'games' => $games,
        ]);
    }
}
