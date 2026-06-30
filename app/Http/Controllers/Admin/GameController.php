<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use App\Services\PredictionScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(
        private readonly PredictionScoringService $scoringService
    ) {}

    public function index(): View
    {
        $games = Game::with(['homeTeam', 'awayTeam'])
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy('stage');

        return view('admin.games.index', compact('games'));
    }

    public function create(): View
    {
        $teams = Team::ordered()->get();

        return view('admin.games.create', compact('teams'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'home_team_id'   => ['required', 'exists:teams,id'],
            'away_team_id'   => ['required', 'exists:teams,id', 'different:home_team_id'],
            'stage'          => ['required', Rule::in(array_keys(Game::STAGES))],
            'match_number'   => ['nullable', 'integer', 'min:1', 'max:999'],
            'group_name'     => ['nullable', 'string', 'size:1', Rule::in(['A','B','C','D','E','F','G','H'])],
            'scheduled_at'   => ['required', 'date'],
            'venue'          => ['nullable', 'string', 'max:150'],
        ], [
            'home_team_id.required'    => 'تیم اول را انتخاب کنید.',
            'away_team_id.required'    => 'تیم دوم را انتخاب کنید.',
            'away_team_id.different'   => 'تیم اول و دوم نباید یکسان باشند.',
            'stage.required'           => 'مرحله بازی را انتخاب کنید.',
            'scheduled_at.required'    => 'تاریخ و ساعت بازی الزامی است.',
        ]);

        Game::create($validated);

        return redirect()->route('admin.games.index')
            ->with('success', 'بازی جدید با موفقیت ثبت شد.');
    }

    public function show(Game $game): View
    {
        $game->load(['homeTeam', 'awayTeam', 'scoringRule']);

        return view('admin.games.show', compact('game'));
    }

    public function edit(Game $game): View
    {
        $teams = Team::ordered()->get();

        return view('admin.games.edit', compact('game', 'teams'));
    }

    public function update(Request $request, Game $game): RedirectResponse
    {
        $validated = $request->validate([
            'home_team_id'   => ['required', 'exists:teams,id'],
            'away_team_id'   => ['required', 'exists:teams,id', 'different:home_team_id'],
            'stage'          => ['required', Rule::in(array_keys(Game::STAGES))],
            'match_number'   => ['nullable', 'integer', 'min:1', 'max:999'],
            'group_name'     => ['nullable', 'string', 'size:1', Rule::in(['A','B','C','D','E','F','G','H'])],
            'scheduled_at'   => ['required', 'date'],
            'venue'          => ['nullable', 'string', 'max:150'],
            'status'         => ['required', Rule::in(['upcoming', 'live', 'finished', 'postponed'])],
            'is_disciplinary' => ['boolean'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        $game->update($validated);

        // اگر وضعیت انضباطی تغییر کرده، امتیازات را بازمحاسبه کن
        if ($game->wasChanged('is_disciplinary') && $game->status === 'finished') {
            $this->scoringService->processGame($game->fresh());
        }

        return redirect()->route('admin.games.index')
            ->with('success', 'بازی با موفقیت به‌روز شد.');
    }

    /**
     * ثبت نتیجه بازی توسط ادمین
     *
     * این اکشن مهم‌ترین عملیات پنل ادمین است:
     * نتیجه ۹۰ دقیقه را ثبت کرده و بلافاصله امتیازات را محاسبه می‌کند.
     */
    public function submitResult(Request $request, Game $game): RedirectResponse
    {
        $validated = $request->validate([
            'home_score'       => ['required', 'integer', 'min:0', 'max:99'],
            'away_score'       => ['required', 'integer', 'min:0', 'max:99'],
            // نتیجه نهایی (اختیاری، فقط برای مسابقات حذفی که به وقت اضافه رفتند)
            'home_score_final' => ['nullable', 'integer', 'min:0', 'max:99'],
            'away_score_final' => ['nullable', 'integer', 'min:0', 'max:99'],
            'winner_team_id'   => ['nullable', 'exists:teams,id'],
        ], [
            'home_score.required' => 'گل تیم اول را وارد کنید.',
            'away_score.required' => 'گل تیم دوم را وارد کنید.',
        ]);

        $game->update([
            ...$validated,
            'status' => 'finished',
        ]);

        // محاسبه خودکار امتیازات
        $result = $this->scoringService->processGame($game->fresh());

        $message = $result['skipped_disciplinary']
            ? 'نتیجه ثبت شد. این بازی انضباطی است و امتیازی محاسبه نشد.'
            : "نتیجه ثبت شد. امتیاز {$result['updated']} پیش‌بینی محاسبه شد.";

        return redirect()->route('admin.games.index')->with('success', $message);
    }

    /**
     * ویرایش نتیجه بازی تمام‌شده + بازمحاسبه امتیازات
     */
    public function updateResult(Request $request, Game $game): RedirectResponse
    {
        $validated = $request->validate([
            'home_score'       => ['required', 'integer', 'min:0', 'max:99'],
            'away_score'       => ['required', 'integer', 'min:0', 'max:99'],
            'home_score_final' => ['nullable', 'integer', 'min:0', 'max:99'],
            'away_score_final' => ['nullable', 'integer', 'min:0', 'max:99'],
        ], [
            'home_score.required' => 'گل تیم اول را وارد کنید.',
            'away_score.required' => 'گل تیم دوم را وارد کنید.',
        ]);

        $game->update($validated);

        // بازمحاسبه امتیازات همه پیش‌بینی‌ها (به جز override شده‌ها)
        $result = $this->scoringService->processGame($game->fresh());

        $message = "نتیجه ویرایش شد. امتیاز {$result['updated']} پیش‌بینی بازمحاسبه شد.";

        return back()->with('success', $message);
    }

    public function destroy(Game $game): RedirectResponse
    {
        $game->delete();

        return redirect()->route('admin.games.index')
            ->with('success', 'بازی حذف شد.');
    }
}
