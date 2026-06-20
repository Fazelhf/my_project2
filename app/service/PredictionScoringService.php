<?php

namespace App\Services;

use App\Models\GamePrediction;
use App\Models\User;

class PredictionScoringService
{
    /**
     * محاسبه امتیاز یک مسابقه خاص بر اساس فرمول شما
     */
    public function calculateGamePoints(int $real1, int $real2, int $pred1, int $pred2): int
    {
        // ۱. نتیجه کاملاً دقیق (۵ امتیاز)
        if ($real1 === $pred1 && $real2 === $pred2) {
            return 5;
        }

        // تفاضل گل‌ها را حساب می‌کنیم
        $realDiff = $real1 - $real2;
        $predDiff = $pred1 - $pred2;

        // مشخص می‌کنیم چه کسی برنده شده: 1 (میزبان), -1 (مهمان), 0 (مساوی)
        $realWinner = $realDiff <=> 0; 
        $predWinner = $predDiff <=> 0;

        // ۲. تفاضل گل درست - در حالتی که بازی مساوی نباشد (۴ امتیاز)
        if ($realDiff === $predDiff && $realWinner !== 0) {
            return 4;
        }

        // ۳. برنده درست یا مساوی درست با نتیجه متفاوت (۳ امتیاز)
        if ($realWinner === $predWinner) {
            return 3;
        }

        // پیش‌بینی کاملاً اشتباه
        return 0;
    }

    /**
     * اعمال امتیازات برای تمام کاربرانی که این مسابقه را پیش‌بینی کرده‌اند
     */
    public function processGameScores(int $gameId, int $realScore1, int $realScore2): void
    {
        // با تکه تکه کردن (chunk) دیتابیس هنگ نمی‌کند اگر هزاران نفر پیش‌بینی کرده باشند
        GamePrediction::where('game_id', $gameId)->chunk(100, function ($predictions) use ($realScore1, $realScore2) {
            foreach ($predictions as $prediction) {
                $points = $this->calculateGamePoints(
                    $realScore1,
                    $realScore2,
                    $prediction->predicted_score1,
                    $prediction->predicted_score2
                );

                if ($points > 0) {
                    // ثبت امتیاز برای این پیش‌بینی خاص
                    $prediction->update(['points_earned' => $points]);
                    
                    // اضافه کردن امتیاز به پروفایل کلی کاربر (نیازمند فیلدهای match_score و total_score در جدول users)
                    User::where('id', $prediction->user_id)->incrementEach([
                        'match_score' => $points,
                        'total_score' => $points,
                    ]);
                }
            }
        });
    }
}