<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'avatar',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * تمام پیش‌بینی‌های این کاربر
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    /**
     * پیش‌بینی‌هایی که امتیاز دریافت کرده‌اند (نتیجه بازی اعلام شده)
     */
    public function scoredPredictions(): HasMany
    {
        return $this->hasMany(Prediction::class)->whereNotNull('points_earned');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /**
     * ترتیب جدول رده‌بندی: امتیاز نزولی، سپس نام
     */
    public function scopeLeaderboard(Builder $query): Builder
    {
        return $query->orderByDesc('total_score')->orderBy('name');
    }

    /**
     * فیلتر کاربران عادی (غیر ادمین)
     */
    public function scopeRegular(Builder $query): Builder
    {
        return $query->where('is_admin', false);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * رتبه کاربر در جدول رده‌بندی
     */
    public function getRankAttribute(): int
    {
        return static::where('total_score', '>', $this->total_score)->count() + 1;
    }

    /**
     * درصد دقت پیش‌بینی (نسبت پیش‌بینی‌های دقیق به کل)
     */
    public function getPredictionAccuracyAttribute(): float
    {
        $total = $this->scoredPredictions()->count();

        if ($total === 0) {
            return 0.0;
        }

        $exact = $this->predictions()->where('points_earned', 10)->count();

        return round(($exact / $total) * 100, 1);
    }

    /**
     * تعداد پیش‌بینی‌های کاملاً دقیق (۱۰ امتیازی)
     */
    public function getExactPredictionsCountAttribute(): int
    {
        return $this->predictions()->where('points_earned', 10)->count();
    }
}
