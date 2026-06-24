<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'match_number',
        'group_name',
        'stage',
        'scheduled_at',
        'venue',
        'home_score',
        'away_score',
        'home_score_final',
        'away_score_final',
        'winner_team_id',
        'status',
        'is_disciplinary',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at'   => 'datetime',
            'is_disciplinary' => 'boolean',
        ];
    }

    // ─── برچسب مراحل مسابقه ──────────────────────────────────────────────────

    const STAGES = [
        'group'        => 'مرحله گروهی',
        'round_of_16'  => 'دور اول حذفی',
        'quarter_final' => 'ربع نهایی',
        'semi_final'   => 'نیمه نهایی',
        'third_place'  => 'رده‌بندی سوم و چهارم',
        'final'        => 'فینال',
    ];

    const KNOCKOUT_STAGES = [
        'round_of_16',
        'quarter_final',
        'semi_final',
        'third_place',
        'final',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /**
     * برنده نهایی - فقط در مسابقات حذفی که به پنالتی رفته استفاده می‌شود
     */
    public function winnerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /**
     * بازی‌های پیش‌رو (مرتب بر اساس زمان)
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 'upcoming')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at');
    }

    /**
     * بازی‌های پایان‌یافته که نتیجه دارند
     */
    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('status', 'finished')
            ->whereNotNull('home_score')
            ->whereNotNull('away_score');
    }

    /**
     * فیلتر بر اساس مرحله مسابقه
     */
    public function scopeOfStage(Builder $query, string $stage): Builder
    {
        return $query->where('stage', $stage);
    }

    /**
     * فیلتر بر اساس گروه (فقط مرحله گروهی)
     */
    public function scopeOfGroup(Builder $query, string $group): Builder
    {
        return $query->where('group_name', strtoupper($group));
    }

    /**
     * بازی‌هایی که امتیازشان قابل محاسبه است
     * (پایان یافته + نتیجه ثبت شده + انضباطی نباشند)
     */
    public function scopeScorable(Builder $query): Builder
    {
        return $query->where('status', 'finished')
            ->whereNotNull('home_score')
            ->whereNotNull('away_score')
            ->where('is_disciplinary', false);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * آیا این بازی در مرحله حذفی است؟
     */
    public function isKnockout(): bool
    {
        return in_array($this->stage, self::KNOCKOUT_STAGES);
    }

    /**
     * آیا پنجره پیش‌بینی بسته شده است؟
     * پیش‌بینی تا ۱۵ دقیقه قبل از شروع بازی مجاز است.
     */
    public function isPredictionLocked(): bool
    {
        return now()->isAfter($this->scheduled_at->subMinutes(15));
    }

    /**
     * آیا این بازی قابل محاسبه امتیاز است؟
     */
    public function isScorable(): bool
    {
        return $this->status === 'finished'
            && $this->home_score !== null
            && $this->away_score !== null
            && ! $this->is_disciplinary;
    }

    /**
     * برچسب فارسی مرحله بازی
     */
    public function getStageLabelAttribute(): string
    {
        return self::STAGES[$this->stage] ?? $this->stage;
    }

    /**
     * نمایش نتیجه ۹۰ دقیقه به فرمت "X - Y"
     */
    public function getScoreDisplayAttribute(): string
    {
        if ($this->home_score === null || $this->away_score === null) {
            return '- vs -';
        }

        return "{$this->home_score} - {$this->away_score}";
    }

    /**
     * نمایش نتیجه نهایی (شامل وقت اضافه) به فرمت "X - Y"
     */
    public function getFinalScoreDisplayAttribute(): string
    {
        $home = $this->home_score_final ?? $this->home_score;
        $away = $this->away_score_final ?? $this->away_score;

        if ($home === null || $away === null) {
            return '- vs -';
        }

        return "{$home} - {$away}";
    }
}
