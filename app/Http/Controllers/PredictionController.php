<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PredictionController extends Controller
{
    public function index(): Response
    {
        $userId = auth()->id();

        $games = Game::with([
            'homeTeam',
            'awayTeam',
            'predictions' => fn ($q) => $q->where('user_id', $userId),
        ])
        ->orderBy('scheduled_at')
        ->get()
        ->groupBy('stage')
        ->map(fn ($stageGames) => $stageGames->map(fn ($g) => [
            'id'                     => $g->id,
            'home_team_id'           => $g->home_team_id,
            'away_team_id'           => $g->away_team_id,
            'home_name'              => $g->homeTeam->name,
            'away_name'              => $g->awayTeam->name,
            'home_code'              => $g->homeTeam->code,
            'away_code'              => $g->awayTeam->code,
            'scheduled_at_formatted' => $g->scheduled_at->timezone('Asia/Tehran')->format('j M H:i'),
            'venue'                  => $g->venue,
            'status'                 => $g->status,
            'finished'               => $g->status === 'finished',
            'locked'                 => $g->isPredictionLocked(),
            'home_score'             => $g->home_score,
            'away_score'             => $g->away_score,
            'prediction'             => $g->predictions->first() ? [
                'home_score'    => $g->predictions->first()->home_score,
                'away_score'    => $g->predictions->first()->away_score,
                'points_earned' => $g->predictions->first()->points_earned,
            ] : null,
        ]));

        return Inertia::render('Games/Index', [
            'gamesByStage' => $games,
        ]);
    }

    public function store(Request $request, Game $game): RedirectResponse
    {
        if ($game->isPredictionLocked()) {
            return back()->with('error', 'زمان پیش‌بینی این بازی به پایان رسیده است.');
        }

        if ($game->status === 'finished') {
            return back()->with('error', 'این بازی پایان یافته است.');
        }

        $validated = $request->validate([
            'home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

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
