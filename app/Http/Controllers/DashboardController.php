<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Live rank: count users with higher live score (points_override ?? points_earned) + adjustment
        $allScores = \App\Models\Prediction::selectRaw('user_id, SUM(COALESCE(points_override, points_earned, 0)) as live')
            ->groupBy('user_id')
            ->pluck('live', 'user_id');

        // Calculate live score for current user including manual adjustment
        $earnedScore = $allScores[$user->id] ?? 0;
        $myLive = max(0, $earnedScore + ($user->score_adjustment ?? 0));

        // Calculate rank based on live score with adjustment
        $users = \App\Models\User::regular()->get();
        $rank = $users->filter(function($u) use ($allScores) {
            $earned = $allScores[$u->id] ?? 0;
            $live = max(0, $earned + ($u->score_adjustment ?? 0));
            return $live > $myLive;
        })->count() + 1;

        $totalPredictions   = Prediction::where('user_id', $user->id)->count();
        $correctPredictions = Prediction::where('user_id', $user->id)->where('points_earned', '>=', 5)->count();
        $exactPredictions   = Prediction::where('user_id', $user->id)->where('points_earned', 10)->count();

        $upcomingGames = Game::with(['homeTeam', 'awayTeam'])
            ->whereIn('status', ['upcoming'])
            ->whereDoesntHave('predictions', fn ($q) => $q->where('user_id', $user->id))
            ->orderBy('scheduled_at')
            ->take(6)
            ->get();

        $predictions = Prediction::with(['game.homeTeam', 'game.awayTeam'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $chatMessages = ChatMessage::with('user')
            ->where('is_deleted', false)
            ->latest()
            ->take(30)
            ->get()
            ->reverse()
            ->values();

        return view('user.dashboard', compact(
            'user', 'rank', 'totalPredictions', 'correctPredictions', 'exactPredictions', 'predictions', 'upcomingGames', 'chatMessages', 'myLive'
        ));
    }
}
