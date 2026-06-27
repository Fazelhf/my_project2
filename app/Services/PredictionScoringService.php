<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameScoringRule;
use App\Models\Prediction;
use App\Models\User;

class PredictionScoringService
{
    /**
     * محاسبه امتیاز همه پیش‌بینی‌های یک بازی پس از ثبت نتیجه
     */
    public function processGame(Game $game): array
    {
        // بازی انضباطی: همه پیش‌بینی‌ها صفر می‌شوند
        if ($game->is_disciplinary) {
            $updated = $game->predictions()->update(['points_earned' => 0]);
            $this->recalculateUserScores();
            return ['updated' => $updated, 'skipped_disciplinary' => true];
        }

        if (!$game->isScorable()) {
            return ['updated' => 0, 'skipped_disciplinary' => false];
        }

        $rule = $game->scoringRule;
        $updated = 0;

        foreach ($game->predictions as $prediction) {
            // اگر ادمین override داده، points_earned رو آپدیت نمی‌کنیم
            if ($prediction->points_override !== null) {
                $updated++;
                continue;
            }

            $points = $rule
                ? $rule->calculatePoints(
                    $prediction->home_score,
                    $prediction->away_score,
                    $game->home_score,
                    $game->away_score
                )
                : $prediction->calculatePoints($game->home_score, $game->away_score);

            $prediction->update(['points_earned' => $points]);
            $updated++;
        }

        $this->recalculateUserScores();

        return ['updated' => $updated, 'skipped_disciplinary' => false];
    }

    /**
     * بازمحاسبه کامل همه امتیازات
     */
    public function recalculateAll(): array
    {
        // ریست همه امتیازها (به جز override های ادمین)
        Prediction::whereNull('points_override')->update(['points_earned' => null]);

        // بازی‌های انضباطی
        Game::where('is_disciplinary', true)
            ->where('status', 'finished')
            ->each(fn(Game $g) => $g->predictions()->update(['points_earned' => 0]));

        $gamesProcessed = 0;
        $predictionsUpdated = 0;

        Game::scorable()->with(['predictions', 'scoringRule'])->get()
            ->each(function (Game $game) use (&$gamesProcessed, &$predictionsUpdated) {
                $rule = $game->scoringRule;

                foreach ($game->predictions as $prediction) {
                    if ($prediction->points_override !== null) {
                        $predictionsUpdated++;
                        continue;
                    }

                    $pts = $rule
                        ? $rule->calculatePoints(
                            $prediction->home_score,
                            $prediction->away_score,
                            $game->home_score,
                            $game->away_score
                        )
                        : $prediction->calculatePoints($game->home_score, $game->away_score);

                    $prediction->update(['points_earned' => $pts]);
                    $predictionsUpdated++;
                }
                $gamesProcessed++;
            });

        $this->recalculateUserScores();

        return [
            'games_processed'     => $gamesProcessed,
            'predictions_updated' => $predictionsUpdated,
        ];
    }

    public function recalculateUserScores(): void
    {
        User::all()->each(function (User $user) {
            // از effective_points استفاده می‌کنیم (override > earned)
            $total = $user->predictions()
                ->selectRaw('COALESCE(points_override, points_earned) as pts')
                ->get()
                ->whereNotNull('pts')
                ->sum('pts');

            $user->update(['total_score' => $total]);
        });
    }
}
