<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $rank = User::where('total_score', '>', $user->total_score)->count() + 1;
        $totalUsers = User::count();

        $predictionsMade = Prediction::where('user_id', $user->id)->count();
        $exactPredictions = Prediction::where('user_id', $user->id)->where('points_earned', 10)->count();
        $scoredPredictions = Prediction::where('user_id', $user->id)->whereNotNull('points_earned')->count();
        $correctPredictions = Prediction::where('user_id', $user->id)->where('points_earned', '>=', 5)->count();

        $accuracy = $scoredPredictions > 0 ? round(($correctPredictions / $scoredPredictions) * 100, 1) : 0;

        $recentPredictions = Prediction::with(['game.homeTeam', 'game.awayTeam'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('user.dashboard', compact(
            'user', 'rank', 'totalUsers',
            'predictionsMade', 'exactPredictions', 'scoredPredictions',
            'correctPredictions', 'accuracy', 'recentPredictions'
        ));
    }
}
