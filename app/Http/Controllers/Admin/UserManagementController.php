<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Prediction;
use App\Models\User;
use App\Services\PredictionScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(private readonly PredictionScoringService $scorer) {}

    // ── لیست کاربران ─────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = User::regular()
            ->withCount(['predictions', 'scoredPredictions as exact_count' => fn($q) => $q->where('points_earned', 10)])
            ->orderByDesc('total_score');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")->orWhere('department', 'like', "%{$s}%"));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $users       = $query->paginate(20)->withQueryString();
        $departments = User::regular()->whereNotNull('department')->distinct()->pluck('department')->sort()->values();
        $stats       = [
            'total'    => User::regular()->count(),
            'active'   => User::regular()->where('is_active', true)->count(),
            'inactive' => User::regular()->where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'departments', 'stats'));
    }

    // ── پروفایل کاربر ────────────────────────────────────────────────────────

    public function show(User $user): View
    {
        $predictions = Prediction::with('game.homeTeam', 'game.awayTeam')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        $auditLogs = AdminAuditLog::forTarget('User', $user->id)
            ->with('admin')
            ->recent()
            ->limit(20)
            ->get();

        $stats = [
            'total_predictions' => $user->predictions()->count(),
            'exact'             => $user->predictions()->where('points_earned', 10)->count(),
            'diff'              => $user->predictions()->where('points_earned', 7)->count(),
            'outcome'           => $user->predictions()->where('points_earned', 5)->count(),
            'participation'     => $user->predictions()->where('points_earned', 2)->count(),
        ];

        return view('admin.users.show', compact('user', 'predictions', 'auditLogs', 'stats'));
    }

    // ── فعال/غیرفعال ─────────────────────────────────────────────────────────

    public function toggleActive(User $user, Request $request): RedirectResponse
    {
        $before = ['is_active' => $user->is_active];
        $user->update(['is_active' => ! $user->is_active]);

        AdminAuditLog::record(
            $user->is_active ? 'user_activated' : 'user_deactivated',
            'User',
            $user->id,
            $before,
            ['is_active' => $user->is_active],
            $request->reason ?? ''
        );

        $label = $user->is_active ? 'فعال' : 'غیرفعال';
        return back()->with('success', "حساب {$user->name} {$label} شد.");
    }

    // ── بروزرسانی یادداشت ادمین ──────────────────────────────────────────────

    public function updateNote(User $user, Request $request): RedirectResponse
    {
        $request->validate(['admin_note' => 'nullable|string|max:1000']);

        $before = ['admin_note' => $user->admin_note];
        $user->update(['admin_note' => $request->admin_note]);

        AdminAuditLog::record('user_note_updated', 'User', $user->id, $before, ['admin_note' => $request->admin_note]);

        return back()->with('success', 'یادداشت بروزرسانی شد.');
    }

    // ── تنظیم دستی امتیاز کاربر ──────────────────────────────────────────────

    public function manualOverride(User $user, Request $request): RedirectResponse
    {
        $request->validate([
            'adjustment' => 'required|integer|min:-9999|max:9999',
            'reason'     => 'required|string|max:500',
        ], [
            'adjustment.required' => 'مقدار تنظیم الزامی است.',
            'reason.required'     => 'دلیل تغییر الزامی است.',
        ]);

        $before = ['score_adjustment' => $user->score_adjustment];
        $user->update(['score_adjustment' => $user->score_adjustment + $request->adjustment]);

        AdminAuditLog::record(
            'score_override',
            'User',
            $user->id,
            $before,
            ['score_adjustment' => $user->score_adjustment],
            $request->reason
        );

        return back()->with('success', "امتیاز {$user->name} با {$request->adjustment} واحد تنظیم شد.");
    }

    // ── ویرایش پیش‌بینی توسط ادمین ───────────────────────────────────────────

    public function editPrediction(Prediction $prediction, Request $request): RedirectResponse
    {
        $request->validate([
            'home_score' => 'required|integer|min:0|max:99',
            'away_score' => 'required|integer|min:0|max:99',
            'admin_note' => 'required|string|max:500',
        ]);

        $before = ['home_score' => $prediction->home_score, 'away_score' => $prediction->away_score];

        $prediction->update([
            'home_score'      => $request->home_score,
            'away_score'      => $request->away_score,
            'is_admin_edited' => true,
            'admin_note'      => $request->admin_note,
            'points_earned'   => null, // نیاز به بازمحاسبه
        ]);

        // بازمحاسبه امتیاز این پیش‌بینی
        if ($prediction->game->isScorable()) {
            $game = $prediction->game()->with('scoringRule')->first();
            $rule = $game->scoringRule;
            $pts  = $rule
                ? $rule->calculatePoints($request->home_score, $request->away_score, $game->home_score, $game->away_score)
                : $prediction->calculatePoints($game->home_score, $game->away_score);
            $prediction->update(['points_earned' => $pts]);
            $this->scorer->recalculateUserScores();
        }

        AdminAuditLog::record(
            'prediction_edited',
            'Prediction',
            $prediction->id,
            $before,
            ['home_score' => $request->home_score, 'away_score' => $request->away_score],
            $request->admin_note
        );

        return back()->with('success', 'پیش‌بینی ویرایش شد.');
    }

    // ── Override امتیاز یک پیش‌بینی ──────────────────────────────────────────

    public function overridePredictionPoints(Prediction $prediction, Request $request): RedirectResponse
    {
        $request->validate([
            'points_override' => 'nullable|integer|min:0|max:100',
            'admin_note'      => 'required|string|max:500',
        ]);

        $before = ['points_override' => $prediction->points_override];

        $prediction->update([
            'points_override' => $request->points_override,
            'admin_note'      => $request->admin_note,
        ]);

        $this->scorer->recalculateUserScores();

        AdminAuditLog::record(
            'prediction_points_override',
            'Prediction',
            $prediction->id,
            $before,
            ['points_override' => $request->points_override],
            $request->admin_note
        );

        return back()->with('success', 'امتیاز پیش‌بینی override شد.');
    }

    // ── عملیات گروهی ─────────────────────────────────────────────────────────

    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action'     => 'required|in:activate,deactivate,score_adjust',
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'amount'     => 'required_if:action,score_adjust|integer|min:-9999|max:9999',
            'reason'     => 'required|string|max:500',
        ]);

        $users  = User::whereIn('id', $request->user_ids)->regular()->get();
        $count  = $users->count();

        match ($request->action) {
            'activate'     => $this->bulkToggle($users, true, $request->reason),
            'deactivate'   => $this->bulkToggle($users, false, $request->reason),
            'score_adjust' => $this->bulkScoreAdjust($users, $request->amount, $request->reason),
        };

        $label = match ($request->action) {
            'activate'     => 'فعال شدند',
            'deactivate'   => 'غیرفعال شدند',
            'score_adjust' => "امتیازشان {$request->amount} واحد تنظیم شد",
        };

        return back()->with('success', "{$count} کاربر {$label}.");
    }

    private function bulkToggle($users, bool $active, string $reason): void
    {
        $action = $active ? 'bulk_activate' : 'bulk_deactivate';
        foreach ($users as $u) {
            $before = ['is_active' => $u->is_active];
            $u->update(['is_active' => $active]);
            AdminAuditLog::record($action, 'User', $u->id, $before, ['is_active' => $active], $reason);
        }
    }

    private function bulkScoreAdjust($users, int $amount, string $reason): void
    {
        foreach ($users as $u) {
            $before = ['score_adjustment' => $u->score_adjustment];
            $u->update(['score_adjustment' => $u->score_adjustment + $amount]);
            AdminAuditLog::record('bulk_score_adjust', 'User', $u->id, $before, ['score_adjustment' => $u->score_adjustment], $reason);
        }
    }
}
