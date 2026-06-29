<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TournamentPrediction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TournamentPredictionController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $teams = Team::orderBy('name')->get();
        $prediction = TournamentPrediction::with(['champion', 'runnerUp', 'thirdPlace'])
            ->where('user_id', $user->id)
            ->first();

        $lockTime = $this->getLockTime();
        $isLocked = $lockTime && now()->isAfter($lockTime);

        // For admin: actual results
        $actualChampion = $this->getSetting('actual_champion');
        $actualRunnerUp = $this->getSetting('actual_runner_up');
        $actualThirdPlace = $this->getSetting('actual_third_place');

        return view('user.tournament-prediction-new', compact(
            'teams', 'prediction', 'isLocked', 'lockTime',
            'actualChampion', 'actualRunnerUp', 'actualThirdPlace'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $lockTime = $this->getLockTime();
        if ($lockTime && now()->isAfter($lockTime)) {
            return back()->with('error', 'زمان ثبت پیش‌بینی قهرمانی پایان یافته است.');
        }

        $validated = $request->validate([
            'champion_team_id'    => ['required', 'exists:teams,id'],
            'runner_up_team_id'   => ['required', 'exists:teams,id'],
            'third_place_team_id' => ['required', 'exists:teams,id'],
        ], [
            'champion_team_id.required'    => 'لطفاً قهرمان را انتخاب کنید.',
            'runner_up_team_id.required'   => 'لطفاً نایب‌قهرمان را انتخاب کنید.',
            'third_place_team_id.required' => 'لطفاً تیم سوم را انتخاب کنید.',
        ]);

        // Ensure distinct teams
        $ids = [$validated['champion_team_id'], $validated['runner_up_team_id'], $validated['third_place_team_id']];
        if (count(array_unique($ids)) < 3) {
            return back()->withErrors(['champion_team_id' => 'قهرمان، نایب‌قهرمان و تیم سوم باید متفاوت باشند.']);
        }

        TournamentPrediction::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'champion_team_id'    => $validated['champion_team_id'],
                'runner_up_team_id'   => $validated['runner_up_team_id'],
                'third_place_team_id' => $validated['third_place_team_id'],
            ]
        );

        return back()->with('success', 'پیش‌بینی قهرمانی شما ثبت شد!');
    }

    private function getLockTime(): ?\Carbon\Carbon
    {
        $val = $this->getSetting('prediction_lock_time');
        return $val ? \Carbon\Carbon::parse($val) : null;
    }

    private function getSetting(string $key): ?string
    {
        return \DB::table('tournament_settings')->where('key', $key)->value('value');
    }
}
