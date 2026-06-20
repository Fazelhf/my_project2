<?php
// app/Http/Controllers/LeaderboardController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MatchPrediction;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        // ۱. دریافت تمام کاربران به ترتیب بالاترین امتیاز کل
        $usersData = User::orderBy('total_score', 'desc')
            ->orderBy('match_score', 'desc') // لایه دوم مرتب‌سازی در صورت تساوی امتیاز کل
            ->get();

        $leaderboard = $usersData->map(function ($user, $index) {
            $predictionsCount = MatchPrediction::where('user_id', $user->id)->count();
            
            // محاسبه تعداد نتایج کاملاً دقیق (امتیاز ۵)
            $exactScoresCount = MatchPrediction::where('user_id', $user->id)
                ->where('points_earned', 5)
                ->count();

            $correctCount = MatchPrediction::where('user_id', $user->id)
                ->where('points_earned', '>', 0)
                ->count();
                
            $accuracy = $predictionsCount > 0 ? round(($correctCount / $predictionsCount) * 100, 1) : 0;

            return [
                'rank' => $index + 1,
                'name' => $user->id == session('user_id') ? session('user_name') . ' (شما)' : $user->name,
                'tournament_score' => $user->tournament_score ?? 0,
                'match_score' => $user->match_score ?? 0,
                'total_score' => $user->total_score ?? 0,
                'predictions' => $predictionsCount,
                'accuracy' => $accuracy,
                'exact_scores' => $exactScoresCount,
                'change' => 0 // رتبه خنثی (می‌توان با ذخیره رتبه‌های روز قبل داینامیک‌تر شود)
            ];
        });

        // ۲. استخراج برترین‌های جام (Top Predictors) برای کارت‌های بالای صفحه
        
        // الف) یافتن کاربر با بیشترین حدس دقیق (امتیاز ۵)
        $mostExact = MatchPrediction::select('user_id', DB::raw('count(*) as total'))
            ->where('points_earned', 5)
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->first();
        $mostExactName = $mostExact ? User::find($mostExact->user_id)->name : '---';
        $mostExactValue = $mostExact ? $mostExact->total : 0;

        // ب) یافتن کاربر با بیشترین تعداد پیش‌بینی ثبت شده
        $mostPredictions = MatchPrediction::select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->first();
        $mostPredictionsName = $mostPredictions ? User::find($mostPredictions->user_id)->name : '---';
        $mostPredictionsValue = $mostPredictions ? $mostPredictions->total : 0;

        $topPredictors = [
            [
                'title' => 'بیشترین نتیجه دقیق',
                'name' => $mostExactName,
                'value' => $mostExactValue,
                'icon' => 'target'
            ],
            [
                'title' => 'بالاترین درصد موفقیت',
                'name' => $leaderboard->first()['name'] ?? '---',
                'value' => ($leaderboard->first()['accuracy'] ?? 0) . '%',
                'icon' => 'chart'
            ],
            [
                'title' => 'بیشترین پیش‌بینی',
                'name' => $mostPredictionsName,
                'value' => $mostPredictionsValue,
                'icon' => 'users'
            ],
        ];

        return view('leaderboard', compact('leaderboard', 'topPredictors'));
    }
}