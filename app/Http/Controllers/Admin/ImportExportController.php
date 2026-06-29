<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImportExportController extends Controller
{
    // ── Export all users' predictions ────────────────────────────────────────

    public function exportAll(): JsonResponse
    {
        $predictions = Prediction::with(['user:id,name,email', 'game:id,home_team_id,away_team_id,scheduled_at'])
            ->get()
            ->map(fn ($p) => [
                'user_id'       => $p->user_id,
                'user_name'     => $p->user->name,
                'user_email'    => $p->user->email,
                'game_id'       => $p->game_id,
                'home_score'    => $p->home_score,
                'away_score'    => $p->away_score,
                'points_earned' => $p->points_earned,
            ]);

        return response()->json($predictions)
            ->header('Content-Disposition', 'attachment; filename="predictions_all.json"');
    }

    // ── Export a single user's predictions ───────────────────────────────────

    public function exportUser(User $user): JsonResponse
    {
        $predictions = $user->predictions()
            ->get()
            ->map(fn ($p) => [
                'game_id'       => $p->game_id,
                'home_score'    => $p->home_score,
                'away_score'    => $p->away_score,
                'points_earned' => $p->points_earned,
            ]);

        return response()->json([
            'user_id'     => $user->id,
            'user_name'   => $user->name,
            'user_email'  => $user->email,
            'predictions' => $predictions,
        ])->header('Content-Disposition', "attachment; filename=\"predictions_user_{$user->id}.json\"");
    }

    // ── Import all users' predictions ────────────────────────────────────────

    public function importAll(Request $request): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimetypes:application/json,text/plain']]);

        $data = json_decode($request->file('file')->get(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return back()->withErrors(['file' => 'فایل JSON معتبر نیست.']);
        }

        foreach ($data as $row) {
            if (!isset($row['user_id'], $row['game_id'])) {
                continue;
            }

            Prediction::updateOrCreate(
                ['user_id' => $row['user_id'], 'game_id' => $row['game_id']],
                [
                    'home_score'    => $row['home_score'] ?? null,
                    'away_score'    => $row['away_score'] ?? null,
                    'points_earned' => $row['points_earned'] ?? null,
                ]
            );
        }

        return back()->with('success', 'پیش‌بینی‌ها با موفقیت وارد شدند.');
    }

    // ── Import a single user's predictions ───────────────────────────────────

    public function importUser(Request $request, User $user): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimetypes:application/json,text/plain']]);

        $data = json_decode($request->file('file')->get(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['predictions']) || !is_array($data['predictions'])) {
            return back()->withErrors(['file' => 'فایل JSON معتبر نیست.']);
        }

        foreach ($data['predictions'] as $row) {
            if (!isset($row['game_id'])) {
                continue;
            }

            Prediction::updateOrCreate(
                ['user_id' => $user->id, 'game_id' => $row['game_id']],
                [
                    'home_score'    => $row['home_score'] ?? null,
                    'away_score'    => $row['away_score'] ?? null,
                    'points_earned' => $row['points_earned'] ?? null,
                ]
            );
        }

        return back()->with('success', "پیش‌بینی‌های {$user->name} با موفقیت وارد شدند.");
    }
}
