<?php

namespace App\Services;

use App\Models\Game;
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

        $updated = 0;
        foreach ($game->predictions as $prediction) {
            $points = $prediction->calculatePoints($game->home_score, $game->away_score);
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
        // ریست همه امتیازها
        Prediction::query()->update(['points_earned' => null]);

        // بازی‌های انضباطی
        Game::where('is_disciplinary', true)
            ->where('status', 'finished')
            ->each(fn(Game $g) => $g->predictions()->update(['points_earned' => 0]));

        $gamesProcessed = 0;
        $predictionsUpdated = 0;

        Game::scorable()->with('predictions')->get()
            ->each(function (Game $game) use (&$gamesProcessed, &$predictionsUpdated) {
                foreach ($game->predictions as $prediction) {
                    $pts = $prediction->calculatePoints($game->home_score, $game->away_score);
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

    private function recalculateUserScores(): void
    {
        User::all()->each(function (User $user) {
            $total = $user->predictions()->whereNotNull('points_earned')->sum('points_earned');
            $user->update(['total_score' => $total]);
        });
    }
}
