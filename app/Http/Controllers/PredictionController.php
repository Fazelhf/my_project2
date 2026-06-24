<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PredictionController extends Controller
{
    /**
     * لیست تمام بازی‌ها به همراه پیش‌بینی کاربر
     */
    public function index(): View
    {
        $userId = auth()->id();

        $games = Game::with([
            'homeTeam',
            'awayTeam',
            'predictions' => fn ($q) => $q->where('user_id', $userId),
        ])
        ->orderBy('scheduled_at')
        ->get()
        ->groupBy('stage');

        return view('user.games.index', compact('games'));
    }

    /**
     * صفحه جزئیات یک بازی
     */
    public function show(Game $game): View
    {
        $game->load(['homeTeam', 'awayTeam']);

        $userPrediction = Prediction::where('user_id', auth()->id())
            ->where('game_id', $game->id)
            ->first();

        return view('user.games.show', compact('game', 'userPrediction'));
    }

    /**
     * ثبت پیش‌بینی جدید
     */
    public function store(Request $request, Game $game): RedirectResponse
    {
        // اگر بازی قفل شده یا تمام شده، نمی‌توان پیش‌بینی ثبت کرد
        if ($game->isPredictionLocked()) {
            return back()->with('error', 'زمان پیش‌بینی این بازی به پایان رسیده است.');
        }

        if ($game->status === 'finished') {
            return back()->with('error', 'این بازی پایان یافته است.');
        }

        $validated = $request->validate([
            'home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ], [
            'home_score.required' => 'گل تیم اول را وارد کنید.',
            'away_score.required' => 'گل تیم دوم را وارد کنید.',
            'home_score.min'      => 'عدد گل نمی‌تواند منفی باشد.',
            'away_score.min'      => 'عدد گل نمی‌تواند منفی باشد.',
        ]);

        // جلوگیری از ثبت تکراری
        $exists = Prediction::where('user_id', auth()->id())
            ->where('game_id', $game->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'شما قبلاً برای این بازی پیش‌بینی ثبت کرده‌اید.');
        }

        Prediction::create([
            'user_id'    => auth()->id(),
            'game_id'    => $game->id,
            'home_score' => $validated['home_score'],
            'away_score' => $validated['away_score'],
        ]);

        return back()->with('success', 'پیش‌بینی شما با موفقیت ثبت شد.');
    }

    /**
     * ویرایش پیش‌بینی موجود (تا قبل از قفل شدن بازی)
     */
    public function update(Request $request, Game $game): RedirectResponse
    {
        if ($game->isPredictionLocked()) {
            return back()->with('error', 'زمان ویرایش پیش‌بینی این بازی به پایان رسیده است.');
        }

        $validated = $request->validate([
            'home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $updated = Prediction::where('user_id', auth()->id())
            ->where('game_id', $game->id)
            ->update([
                'home_score' => $validated['home_score'],
                'away_score' => $validated['away_score'],
            ]);

        if (! $updated) {
            return back()->with('error', 'پیش‌بینی‌ای برای ویرایش یافت نشد.');
        }

        return back()->with('success', 'پیش‌بینی شما ویرایش شد.');
    }
}
