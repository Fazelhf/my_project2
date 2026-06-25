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
                'id'                  => $g->id,
                'home_name'           => $g->homeTeam->name,
                'away_name'           => $g->awayTeam->name,
                'home_code'           => $g->homeTeam->code,
                'away_code'           => $g->awayTeam->code,
                'scheduled_at_formatted' => $g->scheduled_at->timezone('Asia/Tehran')->format('j M H:i'),
            ]);

        $recentPredictions = Prediction::with(['game.homeTeam', 'game.awayTeam'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get()
            ->map(fn ($p) => [
                'id'         => $p->id,
                'home_code'  => $p->game->homeTeam->code,
                'away_code'  => $p->game->awayTeam->code,
                'home_score' => $p->home_score,
                'away_score' => $p->away_score,
                'finished'   => $p->game->status === 'finished',
                'real_home'  => $p->game->home_score,
                'real_away'  => $p->game->away_score,
                'points_earned' => $p->points_earned,
            ]);

        return Inertia::render('Dashboard', [
            'user'              => $user,
            'rank'              => $rank,
            'accuracy'          => $accuracy,
            'totalPredictions'  => $totalPredictions,
            'upcomingGames'     => $upcomingGames,
            'recentPredictions' => $recentPredictions,
        ]);
    }
}
