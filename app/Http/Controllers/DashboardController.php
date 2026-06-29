<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $rank = User::where('total_score', '>', $user->total_score)->count() + 1;

        $totalPredictions   = Prediction::where('user_id', $user->id)->count();
        $correctPredictions = Prediction::where('user_id', $user->id)->where('points_earned', '>=', 5)->count();
        $exactPredictions   = Prediction::where('user_id', $user->id)->where('points_earned', 10)->count();

        $upcomingGames = Game::with(['homeTeam', 'awayTeam'])
            ->whereIn('status', ['upcoming', 'scheduled'])
            ->whereDoesntHave('predictions', fn ($q) => $q->where('user_id', $user->id))
            ->orderBy('scheduled_at')
            ->take(6)
            ->get();

        $predictions = Prediction::with(['game.homeTeam', 'game.awayTeam'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('user.dashboard', compact(
            'user', 'rank', 'totalPredictions', 'correctPredictions', 'exactPredictions', 'predictions', 'upcomingGames'
        ));
    }
}
