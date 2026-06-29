<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameScoringRule extends Model
{
    protected $fillable = [
        'game_id',
        'points_exact',
        'points_diff',
        'points_outcome',
        'points_participation',
        'multiplier',
        'is_active',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'multiplier' => 'decimal:2',
            'is_active'  => 'boolean',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // مقادیر پیش‌فرض سیستم (بدون قانون سفارشی)
    public static function defaults(): array
    {
        return [
            'points_exact'         => 10,
            'points_diff'          => 7,
            'points_outcome'       => 5,
            'points_participation' => 2,
            'multiplier'           => 1.00,
        ];
    }

    // محاسبه امتیاز بر اساس این قانون
    public function calculatePoints(int $predHome, int $predAway, int $realHome, int $realAway): int
    {
        if (! $this->is_active) {
            return 0;
        }

        $base = match (true) {
            $predHome === $realHome && $predAway === $realAway
                => $this->points_exact,
            ($predHome - $predAway) === ($realHome - $realAway)
                => $this->points_diff,
            ($predHome <=> $predAway) === ($realHome <=> $realAway)
                => $this->points_outcome,
            default
                => $this->points_participation,
        };

        return (int) round($base * $this->multiplier);
    }
}
