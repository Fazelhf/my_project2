<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GamePrediction; // استفاده از مدل جدید
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $user = User::findOrFail($userId);

        // جلوگیری از ارور null: اگر کاربر امتیازی نداشت، صفر در نظر می‌گیریم
        $currentTournamentScore = $user->tournament_score ?? 0;
        $currentMatchScore = $user->match_score ?? 0;
        $currentTotalScore = $user->total_score ?? 0;

        // ۱. محاسبه رتبه زنده
        $rank = User::where('total_score', '>', $currentTotalScore)->count() + 1;
        $totalUsers = User::count();

        // ۲. آمار پیش‌بینی‌های بازی‌ها
        $predictionsMade = GamePrediction::where('user_id', $userId)->count();
        $correctPredictions = GamePrediction::where('user_id', $userId)
            ->where('points_earned', '>', 0)
            ->count();
            
        $accuracy = $predictionsMade > 0 ? round(($correctPredictions / $predictionsMade) * 100, 1) : 0;

        $stats = [
            'tournament_score' => $currentTournamentScore,
            'match_score' => $currentMatchScore,
            'total_score' => $currentTotalScore,
            'rank' => $rank,
            'total_users' => $totalUsers,
            'predictions_made' => $predictionsMade,
            'correct_predictions' => $correctPredictions,
            'accuracy' => $accuracy
        ];

        // ۳. ۵ پیش‌بینی اخیر کاربر برای نمایش در جدول
        $recentPredictions = GamePrediction::with(['game.team1', 'game.team2'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentMatches = $recentPredictions->map(function ($pred) {
            return [
                'team1' => $pred->game->team1->name,
                'team2' => $pred->game->team2->name,
                'predicted' => "{$pred->predicted_score1}-{$pred->predicted_score2}",
                'actual' => $pred->game->status === 'finished' ? "{$pred->game->real_score1}-{$pred->game->real_score2}" : null,
                'points' => $pred->points_earned,
                'status' => $pred->game->status
            ];
        });

        // ۴. داده‌های چارت گرافیکی
        $scoreHistory = GamePrediction::join('games', 'game_predictions.game_id', '=', 'games.id')
            ->where('game_predictions.user_id', $userId)
            ->where('games.status', 'finished')
            ->orderBy('games.game_datetime', 'asc')
            ->select('game_predictions.points_earned')
            ->get();

        $chartLabels = ['شروع جام'];
        $chartData = [$currentTournamentScore]; // نقطه شروع نمودار
        $runningTotal = $currentTournamentScore;

        foreach ($scoreHistory as $index => $history) {
            $runningTotal += $history->points_earned;
            $chartLabels[] = 'بازی ' . ($index + 1);
            $chartData[] = $runningTotal;
        }

        return view('user.dashboard', compact('stats', 'recentMatches', 'chartLabels', 'chartData'));
    }
}