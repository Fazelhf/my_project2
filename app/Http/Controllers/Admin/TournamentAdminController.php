<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TournamentPrediction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TournamentAdminController extends Controller
{
    public function index(): View
    {
        $settings = \DB::table('tournament_settings')->pluck('value', 'key');
        $teams = Team::orderBy('name')->get();
        $predictions = TournamentPrediction::with(['user', 'champion', 'runnerUp', 'thirdPlace'])
            ->orderByDesc('id')
            ->get();

        return view('admin.tournament.index', compact('settings', 'teams', 'predictions'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'prediction_lock_time' => ['nullable', 'date'],
            'actual_champion'      => ['nullable', 'exists:teams,id'],
            'actual_runner_up'     => ['nullable', 'exists:teams,id'],
            'actual_third_place'   => ['nullable', 'exists:teams,id'],
        ]);

        $keys = ['prediction_lock_time', 'actual_champion', 'actual_runner_up', 'actual_third_place'];

        foreach ($keys as $key) {
            \DB::table('tournament_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $request->get($key), 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // Recalculate tournament points if results are set
        if ($request->actual_champion || $request->actual_runner_up || $request->actual_third_place) {
            $this->recalculatePoints(
                $request->actual_champion,
                $request->actual_runner_up,
                $request->actual_third_place
            );
        }

        return back()->with('success', 'تنظیمات پیش‌بینی قهرمانی ذخیره شد.');
    }

    private function recalculatePoints(?int $champion, ?int $runnerUp, ?int $thirdPlace): void
    {
        TournamentPrediction::all()->each(function ($pred) use ($champion, $runnerUp, $thirdPlace) {
            $pred->update([
                'champion_points'    => ($champion && $pred->champion_team_id == $champion) ? 100 : 0,
                'runner_up_points'   => ($runnerUp && $pred->runner_up_team_id == $runnerUp) ? 50 : 0,
                'third_place_points' => ($thirdPlace && $pred->third_place_team_id == $thirdPlace) ? 50 : 0,
            ]);
        });
    }
}
