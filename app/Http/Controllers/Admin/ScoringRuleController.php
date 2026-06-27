<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Game;
use App\Models\GameScoringRule;
use App\Services\PredictionScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScoringRuleController extends Controller
{
    public function __construct(private readonly PredictionScoringService $scorer) {}

    public function index(Request $request): View
    {
        $query = Game::with(['homeTeam', 'awayTeam', 'scoringRule'])
            ->orderBy('scheduled_at');

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }
        if ($request->filled('has_rule')) {
            $query->has('scoringRule', $request->has_rule === 'yes' ? '>=' : '<', 1);
        }

        $games  = $query->paginate(20)->withQueryString();
        $stages = Game::STAGES;

        return view('admin.scoring-rules.index', compact('games', 'stages'));
    }

    public function update(Game $game, Request $request): RedirectResponse
    {
        $request->validate([
            'points_exact'         => 'required|integer|min:0|max:100',
            'points_diff'          => 'required|integer|min:0|max:100',
            'points_outcome'       => 'required|integer|min:0|max:100',
            'points_participation' => 'required|integer|min:0|max:100',
            'multiplier'           => 'required|numeric|min:0|max:10',
            'is_active'            => 'boolean',
            'notes'                => 'nullable|string|max:500',
        ], [
            'points_exact.max'   => 'حداکثر ۱۰۰ امتیاز مجاز است.',
            'multiplier.max'     => 'ضریب حداکثر ۱۰ است.',
        ]);

        $existing = $game->scoringRule;
        $before   = $existing ? $existing->toArray() : null;

        $rule = GameScoringRule::updateOrCreate(
            ['game_id' => $game->id],
            [
                'points_exact'         => $request->points_exact,
                'points_diff'          => $request->points_diff,
                'points_outcome'       => $request->points_outcome,
                'points_participation' => $request->points_participation,
                'multiplier'           => $request->multiplier,
                'is_active'            => $request->boolean('is_active', true),
                'notes'                => $request->notes,
                'created_by'           => $existing ? $existing->created_by : auth()->id(),
                'updated_by'           => auth()->id(),
            ]
        );

        AdminAuditLog::record(
            $existing ? 'scoring_rule_updated' : 'scoring_rule_created',
            'GameScoringRule',
            $rule->id,
            $before ?? [],
            $rule->toArray(),
            $request->notes ?? ''
        );

        // اگر بازی تموم شده، بازمحاسبه کن
        if ($game->isScorable() && $request->boolean('recalculate_now')) {
            $this->scorer->processGame($game->fresh(['predictions', 'scoringRule']));
        }

        return back()->with('success', "قانون امتیازدهی بازی {$game->homeTeam?->name} vs {$game->awayTeam?->name} ذخیره شد.");
    }

    public function destroy(Game $game): RedirectResponse
    {
        $rule = $game->scoringRule;
        if ($rule) {
            AdminAuditLog::record('scoring_rule_updated', 'GameScoringRule', $rule->id, $rule->toArray(), [], 'حذف قانون - بازگشت به پیش‌فرض');
            $rule->delete();
        }
        return back()->with('success', 'قانون حذف شد — سیستم از مقادیر پیش‌فرض استفاده می‌کند.');
    }
}
