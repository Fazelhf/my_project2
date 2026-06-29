<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PredictionController extends Controller
{
    public function index(Request $request): View
    {
        $user   = auth()->user();
        $filter = $request->get('filter', 'all');
        $group  = $request->get('group');

        $query = Game::with([
            'homeTeam',
            'awayTeam',
            'predictions' => fn($q) => $q->where('user_id', $user->id),
        ])->orderBy('scheduled_at');

        match ($filter) {
            'today'    => $query->whereDate('scheduled_at', today()),
            'tomorrow' => $query->whereDate('scheduled_at', today()->addDay()),
            'week'     => $query->whereBetween('scheduled_at', [now(), now()->addWeek()]),
            default    => null,
        };

        if ($group) {
            $query->where('group_name', strtoupper($group));
        }

        $games = $query->get()->groupBy('stage');
        $rank  = $user->rank;

        return view('user.games.index', compact('games', 'rank', 'filter', 'group'));
    }

    public function show(Game $game): View
    {
        $game->load([
            'homeTeam',
            'awayTeam',
            'predictions.user',
        ]);

        $myPrediction = $game->predictions->firstWhere('user_id', auth()->id());

        return view('user.games.show', compact('game', 'myPrediction'));
    }

    public function store(Request $request, Game $game): RedirectResponse
    {
        if ($game->isPredictionLocked()) {
            return back()->with('error', 'زمان ثبت پیش‌بینی پایان یافته است.');
        }

        if ($game->status === 'finished') {
            return back()->with('error', 'این بازی پایان یافته است.');
        }

        $request->validate([
            'home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ], [
            'home_score.required' => 'گل تیم اول را وارد کنید.',
            'away_score.required' => 'گل تیم دوم را وارد کنید.',
        ]);

        // اگر پیش‌بینی قبلی داشت، ویرایش می‌کنیم
        Prediction::updateOrCreate(
            ['user_id' => auth()->id(), 'game_id' => $game->id],
            ['home_score' => $request->home_score, 'away_score' => $request->away_score]
        );

        return back()->with('success', 'پیش‌بینی شما ثبت شد!');
    }

    public function update(Request $request, Game $game): RedirectResponse
    {
        if ($game->isPredictionLocked()) {
            return back()->with('error', 'زمان ویرایش پیش‌بینی پایان یافته است.');
        }

        $request->validate([
            'home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $prediction = Prediction::where('user_id', auth()->id())
            ->where('game_id', $game->id)
            ->firstOrFail();

        $prediction->update([
            'home_score' => $request->home_score,
            'away_score' => $request->away_score,
        ]);

        return back()->with('success', 'پیش‌بینی شما ویرایش شد!');
    }
}
