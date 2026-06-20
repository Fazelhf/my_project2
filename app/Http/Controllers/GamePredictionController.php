<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GamePrediction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GamePredictionController extends Controller
{
    // نمایش تمام بازی‌ها
    public function index()
    {
        $userId = session('user_id');
        
        // دریافت مسابقات و پیش‌بینی‌های کاربر
        $gamesData = Game::with(['predictions' => function($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->orderBy('game_datetime', 'asc')->get();

        $matches = $gamesData->map(function($game) {
            $userPred = $game->predictions->first();
            return [
                'id' => $game->id,
                'team1' => $game->team1->name,
                'team2' => $game->team2->name,
                'date' => Carbon::parse($game->game_datetime)->format('Y-m-d'),
                'time' => Carbon::parse($game->game_datetime)->format('H:i'),
                'stage' => $game->stage, 
                'group' => $game->team1->group_name, // گروه رو از تیم اول می‌گیریم
                'status' => $game->status,
                'score1' => $game->real_score1,
                'score2' => $game->real_score2,
                'predicted' => $userPred ? true : false,
                'pred_score1' => $userPred ? $userPred->predicted_score1 : null,
                'pred_score2' => $userPred ? $userPred->predicted_score2 : null,
                'points_earned' => $userPred ? $userPred->points_earned : 0,
            ];
        });

        return view('user.matches', compact('matches'));
    }

    // صفحه فرم پیش‌بینی برای یک بازی
    public function predict($id)
    {
        $game = Game::findOrFail($id);
        
        if (Carbon::now()->greaterThanOrEqualTo($game->game_datetime)) {
            return redirect()->route('matches')->withErrors(['error' => 'زمان پیش‌بینی این مسابقه به پایان رسیده است.']);
        }

        $userPred = GamePrediction::where('user_id', session('user_id'))->where('game_id', $id)->first();

        $matchData = [
            'id' => $game->id,
            'team1' => $game->team1->name,
            'team2' => $game->team2->name,
            'date' => Carbon::parse($game->game_datetime)->format('Y-m-d'),
            'time' => Carbon::parse($game->game_datetime)->format('H:i'),
            'stage' => $game->stage,
            'group' => $game->team1->group_name,
            'venue' => $game->venue,
            'predicted' => $userPred ? true : false,
            'pred_score1' => $userPred ? $userPred->predicted_score1 : null,
            'pred_score2' => $userPred ? $userPred->predicted_score2 : null
        ];

        return view('user.predict-match', compact('matchData'));
    }

    // ذخیره پیش‌بینی در دیتابیس
    public function storePrediction(Request $request, $id)
    {
        $request->validate([
            'score1' => 'required|integer|min:0',
            'score2' => 'required|integer|min:0',
        ]);

        $game = Game::findOrFail($id);

        if (Carbon::now()->greaterThanOrEqualTo($game->game_datetime)) {
            return back()->withErrors(['error' => 'مسابقه شروع شده و امکان ثبت پیش‌بینی وجود ندارد.']);
        }

        GamePrediction::updateOrCreate(
            [
                'user_id' => session('user_id'),
                'game_id' => $id
            ],
            [
                'predicted_score1' => $request->score1,
                'predicted_score2' => $request->score2,
            ]
        );

        return redirect()->route('matches')->with('success', 'پیش‌بینی شما با موفقیت ثبت شد.');
    }
}lll