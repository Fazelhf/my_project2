<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(): View
    {
        $users = User::regular()->orderBy('name')->get();

        $finishedGames = Game::finished()->with(['homeTeam', 'awayTeam'])->orderBy('scheduled_at')->get();

        $predictions = Prediction::whereIn('user_id', $users->pluck('id'))
            ->get()
            ->groupBy('user_id');

        $users->each(function (User $user) use ($predictions) {
            $predictionScore = $predictions->get($user->id, collect())->sum(function ($p) {
                return $p->points_override ?? $p->points_earned ?? 0;
            });
            $user->live_score = max(0, $predictionScore + ($user->score_adjustment ?? 0));
        });

        $users = $users->sort(function ($a, $b) {
            if ($b->live_score !== $a->live_score) {
                return $b->live_score - $a->live_score;
            }
            return strcmp($a->name, $b->name);
        })->values();

        // Cumulative score history per user for the trend chart
        $chartData = [];
        foreach ($users as $u) {
            $userPreds = $predictions->get($u->id, collect())->keyBy('game_id');
            $cumulative = $u->score_adjustment ?? 0;
            $scores = [$cumulative];
            foreach ($finishedGames as $game) {
                $pred = $userPreds->get($game->id);
                $cumulative += $pred ? ($pred->points_override ?? $pred->points_earned ?? 0) : 0;
                $scores[] = max(0, $cumulative);
            }
            $chartData[] = ['id' => $u->id, 'name' => $u->name, 'scores' => $scores];
        }
        $chartLabels = array_merge(['شروع'], $finishedGames->map(fn($g, $i) => 'م' . ($i + 1))->toArray());

        $knockoutGames = Game::with(['homeTeam', 'awayTeam', 'winnerTeam'])
            ->whereIn('stage', Game::KNOCKOUT_STAGES)
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy('stage');

        return view('user.leaderboard', compact('users', 'finishedGames', 'predictions', 'chartData', 'chartLabels', 'knockoutGames'));
    }
}
