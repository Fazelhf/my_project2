<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use App\Services\PredictionScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly PredictionScoringService $scoringService
    ) {}

    public function index(): View
    {
        $stats = [
            'total_users'       => User::regular()->count(),
            'total_teams'       => Team::count(),
            'total_games'       => Game::count(),
            'finished_games'    => Game::where('status', 'finished')->count(),
            'upcoming_games'    => Game::where('status', 'upcoming')->count(),
            'total_predictions' => Prediction::count(),
            'scored_predictions' => Prediction::whereNotNull('points_earned')->count(),
        ];

        // بازی‌های اخیر که نتیجه دارند
        $recentGames = Game::with(['homeTeam', 'awayTeam'])
            ->where('status', 'finished')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // بازی‌های پیش‌رو
        $upcomingGames = Game::with(['homeTeam', 'awayTeam'])
            ->upcoming()
            ->take(5)
            ->get();

        // لاگ فعالیت‌های اخیر (پیش‌بینی‌های اخیر ثبت‌شده)
        $recentActivity = \App\Models\Prediction::with(['user', 'game.homeTeam', 'game.awayTeam'])
            ->latest()
            ->take(8)
            ->get();

        $users = User::regular()->orderBy('name')->get();

        return view('admin.dashboard', compact('stats', 'recentGames', 'upcomingGames', 'recentActivity', 'users'));
    }

    /**
     * بازمحاسبه کامل تمام امتیازات
     * دکمه‌ای در پنل ادمین این متد را صدا می‌زند
     */
    public function recalculateScores(Request $request): RedirectResponse
    {
        $result = $this->scoringService->recalculateAll();

        return redirect()->route('admin.dashboard')->with('success',
            "امتیازات بازمحاسبه شد. {$result['games_processed']} بازی پردازش و {$result['predictions_updated']} پیش‌بینی به‌روز شد."
        );
    }
}
