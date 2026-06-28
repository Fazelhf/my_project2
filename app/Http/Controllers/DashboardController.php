<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        $rank = User::where('total_score', '>', $user->total_score)->count() + 1;

        $scoredPredictions  = Prediction::where('user_id', $user->id)->whereNotNull('points_earned')->count();
        $correctPredictions = Prediction::where('user_id', $user->id)->where('points_earned', '>=', 5)->count();
        $totalPredictions   = Prediction::where('user_id', $user->id)->count();
        $accuracy           = $scoredPredictions > 0 ? round(($correctPredictions / $scoredPredictions) * 100, 1) : 0;

        $upcomingGames = Game::with(['homeTeam', 'awayTeam'])
            ->where('status', 'scheduled')
            ->whereDoesntHave('predictions', fn ($q) => $q->where('user_id', $user->id))
            ->orderBy('scheduled_at')
            ->take(6)
            ->get()
            ->map(fn ($g) => [
                'id'                     => $g->id,
                'home_name'              => $g->homeTeam->name,
                'away_name'              => $g->awayTeam->name,
                'home_code'              => $g->homeTeam->code,
                'away_code'              => $g->awayTeam->code,
                'scheduled_at_formatted' => $g->scheduled_at->timezone('Asia/Tehran')->format('j M H:i'),
                'venue'                  => $g->venue,
            ]);

        $recentPredictions = Prediction::with(['game.homeTeam', 'game.awayTeam'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get()
            ->map(fn ($p) => [
                'id'            => $p->id,
                'home_score'    => $p->home_score,
                'away_score'    => $p->away_score,
                'points_earned' => $p->points_earned,
                'game'          => [
                    'id'         => $p->game->id,
                    'home_name'  => $p->game->homeTeam->name,
                    'away_name'  => $p->game->awayTeam->name,
                    'home_code'  => $p->game->homeTeam->code,
                    'away_code'  => $p->game->awayTeam->code,
                    'home_score' => $p->game->home_score,
                    'away_score' => $p->game->away_score,
                    'status'     => $p->game->status,
                ],
            ]);

        return Inertia::render('Dashboard', [
            'userStats' => [
                'rank'             => $rank,
                'total_score'      => $user->total_score,
                'accuracy'         => $accuracy,
                'totalPredictions' => $totalPredictions,
            ],
            'upcomingGames'     => $upcomingGames,
            'recentPredictions' => $recentPredictions,
        ]);
    }
}
