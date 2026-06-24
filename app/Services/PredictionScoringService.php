<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PredictionScoringService
{
    /**
     * محاسبه و ذخیره امتیاز تمام پیش‌بینی‌های یک بازی
     *
     * این متد فراخوانده می‌شود وقتی ادمین نتیجه یک بازی را ثبت می‌کند.
     * اگر بازی انضباطی باشد، هیچ امتیازی محاسبه نمی‌شود.
     *
     * @param  Game  $game  بازی‌ای که نتیجه‌اش تازه ثبت شده
     * @return array{updated: int, skipped_disciplinary: bool}
     */
    public function processGame(Game $game): array
    {
        // بازی‌های انضباطی از محاسبات خارج هستند
        if ($game->is_disciplinary) {
            Prediction::where('game_id', $game->id)
                ->update(['points_earned' => 0]);

            return [
                'updated'                => 0,
                'skipped_disciplinary'   => true,
            ];
        }

        if (! $game->isScorable()) {
            return [
                'updated'              => 0,
                'skipped_disciplinary' => false,
            ];
        }

        $updated = 0;

        DB::transaction(function () use ($game, &$updated): void {
            $predictions = Prediction::where('game_id', $game->id)->get();

            foreach ($predictions as $prediction) {
                $points = $prediction->calculatePoints(
                    $game->home_score,
                    $game->away_score
                );

                $prediction->update(['points_earned' => $points]);
                $updated++;
            }
        });

        // بعد از محاسبه امتیازات بازی، total_score کاربران به‌روز می‌شود
        $this->recalculateUserTotals();

        return [
            'updated'              => $updated,
            'skipped_disciplinary' => false,
        ];
    }

    /**
     * بازمحاسبه کامل امتیازات تمام بازی‌ها
     *
     * برای مواقعی که ادمین نتیجه بازی را ویرایش می‌کند یا
     * وضعیت انضباطی را تغییر می‌دهد.
     *
     * @return array{games_processed: int, predictions_updated: int}
     */
    public function recalculateAll(): array
    {
        $gamesProcessed      = 0;
        $predictionsUpdated  = 0;

        DB::transaction(function () use (&$gamesProcessed, &$predictionsUpdated): void {
            // ابتدا همه امتیازات را null می‌کنیم
            Prediction::query()->update(['points_earned' => null]);

            // سپس برای هر بازی که نتیجه دارد محاسبه می‌کنیم
            $finishedGames = Game::where('status', 'finished')
                ->whereNotNull('home_score')
                ->whereNotNull('away_score')
                ->get();

            foreach ($finishedGames as $game) {
                $gamesProcessed++;

                if ($game->is_disciplinary) {
                    Prediction::where('game_id', $game->id)
                        ->update(['points_earned' => 0]);
                    continue;
                }

                $predictions = Prediction::where('game_id', $game->id)->get();

                foreach ($predictions as $prediction) {
                    $points = $prediction->calculatePoints(
                        $game->home_score,
                        $game->away_score
                    );

                    $prediction->update(['points_earned' => $points]);
                    $predictionsUpdated++;
                }
            }
        });

        $this->recalculateUserTotals();

        return [
            'games_processed'    => $gamesProcessed,
            'predictions_updated' => $predictionsUpdated,
        ];
    }

    /**
     * به‌روزرسانی total_score برای تمام کاربران
     *
     * از یک Query مستقیم روی دیتابیس استفاده می‌کند تا
     * به‌جای N+1 query، فقط یک UPDATE اجرا شود.
     */
    public function recalculateUserTotals(): void
    {
        DB::statement('
            UPDATE users
            SET total_score = (
                SELECT COALESCE(SUM(points_earned), 0)
                FROM predictions
                WHERE predictions.user_id = users.id
                  AND predictions.points_earned IS NOT NULL
            )
        ');
    }

    /**
     * خلاصه وضعیت امتیازدهی یک بازی
     *
     * @return array{total: int, scored: int, pending: int, breakdown: array}
     */
    public function getGameStats(Game $game): array
    {
        $predictions = Prediction::where('game_id', $game->id)
            ->selectRaw('points_earned, COUNT(*) as count')
            ->groupBy('points_earned')
            ->get()
            ->keyBy('points_earned');

        $total  = Prediction::where('game_id', $game->id)->count();
        $scored = Prediction::where('game_id', $game->id)
            ->whereNotNull('points_earned')
            ->count();

        return [
            'total'     => $total,
            'scored'    => $scored,
            'pending'   => $total - $scored,
            'breakdown' => [
                10 => $predictions[10]->count ?? 0,
                7  => $predictions[7]->count ?? 0,
                5  => $predictions[5]->count ?? 0,
                2  => $predictions[2]->count ?? 0,
                0  => $predictions[0]->count ?? 0,
            ],
        ];
    }
}
