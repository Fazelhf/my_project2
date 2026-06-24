<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'home_score',
        'away_score',
        'points_earned',
    ];

    protected function casts(): array
    {
        return [
            'home_score'    => 'integer',
            'away_score'    => 'integer',
            'points_earned' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    // ─── منطق امتیازدهی ───────────────────────────────────────────────────────

    /**
     * محاسبه امتیاز پیش‌بینی بر اساس نتیجه واقعی ۹۰ دقیقه
     *
     * قوانین امتیازدهی:
     * ┌──────┬──────────────────────────────────────────────────────────────┐
     * │  10  │ پیش‌بینی دقیق: نتیجه کاملاً درست                            │
     * │      │ مثال: بازی 2-1 شد، پیش‌بینی 2-1 بود                         │
     * ├──────┼──────────────────────────────────────────────────────────────┤
     * │   7  │ اختلاف گل درست: برنده درست + تفاضل گل یکسان (اما نه دقیق)  │
     * │      │ مثال: بازی 3-1 شد (فرق=2)، پیش‌بینی 2-0 بود (فرق=2)        │
     * │      │ یا: بازی مساوی شد و کاربر مساوی پیش‌بینی کرده بود           │
     * │      │ مثال: بازی 2-2 شد، پیش‌بینی 1-1 بود                         │
     * ├──────┼──────────────────────────────────────────────────────────────┤
     * │   5  │ روند درست: تیم برنده درست اما اختلاف گل غلط                 │
     * │      │ مثال: بازی 1-0 شد، پیش‌بینی 2-0 بود                         │
     * ├──────┼──────────────────────────────────────────────────────────────┤
     * │   2  │ شرکت در مسابقه اما پیش‌بینی اشتباه                          │
     * │      │ مثال: بازی 1-0 شد، پیش‌بینی 1-1 بود                         │
     * └──────┴──────────────────────────────────────────────────────────────┘
     *
     * @param int $realHome  گل تیم اول در ۹۰ دقیقه قانونی
     * @param int $realAway  گل تیم دوم در ۹۰ دقیقه قانونی
     */
    public function calculatePoints(int $realHome, int $realAway): int
    {
        $predHome = $this->home_score;
        $predAway = $this->away_score;

        // ── ۱۰ امتیاز: پیش‌بینی دقیق ─────────────────────────────────────
        if ($predHome === $realHome && $predAway === $realAway) {
            return 10;
        }

        $realDiff = $realHome - $realAway;   // تفاضل گل واقعی (می‌تواند منفی باشد)
        $predDiff = $predHome - $predAway;   // تفاضل گل پیش‌بینی

        // ── ۷ امتیاز: اختلاف گل یکسان ────────────────────────────────────
        // حالت ۱: هر دو مساوی (اما نتیجه متفاوت - چون حالت دقیق بالا handle شد)
        // حالت ۲: برنده یکسان و تفاضل گل برابر
        if ($realDiff === $predDiff) {
            return 7;
        }

        // ── ۵ امتیاز: روند درست (برنده/بازنده/مساوی) ─────────────────────
        // spaceship operator: -1 (away wins), 0 (draw), 1 (home wins)
        $realOutcome = $realHome <=> $realAway;
        $predOutcome = $predHome <=> $predAway;

        if ($realOutcome === $predOutcome) {
            return 5;
        }

        // ── ۲ امتیاز: شرکت اما پیش‌بینی اشتباه ────────────────────────────
        return 2;
    }

    /**
     * برچسب امتیاز برای نمایش در UI
     */
    public function getPointsLabelAttribute(): string
    {
        return match ($this->points_earned) {
            10      => 'پیش‌بینی دقیق',
            7       => 'اختلاف گل درست',
            5       => 'روند درست',
            2       => 'شرکت در مسابقه',
            0       => 'بازی انضباطی',
            null    => 'در انتظار نتیجه',
            default => "{$this->points_earned} امتیاز",
        };
    }

    /**
     * رنگ badge برای نمایش در UI (کلاس Tailwind)
     */
    public function getPointsBadgeColorAttribute(): string
    {
        return match ($this->points_earned) {
            10      => 'bg-emerald-500',
            7       => 'bg-blue-500',
            5       => 'bg-yellow-500',
            2       => 'bg-gray-400',
            0       => 'bg-red-400',
            null    => 'bg-slate-300',
            default => 'bg-slate-400',
        };
    }

    /**
     * نمایش پیش‌بینی به فرمت "X - Y"
     */
    public function getDisplayAttribute(): string
    {
        return "{$this->home_score} - {$this->away_score}";
    }
}
