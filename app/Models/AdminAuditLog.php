<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'before',
        'after',
        'reason',
        'ip_address',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'before'     => 'array',
            'after'      => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopeForTarget(Builder $query, string $type, int $id): Builder
    {
        return $query->where('target_type', $type)->where('target_id', $id);
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    // Helper: ثبت log با یک خط
    public static function record(
        string $action,
        string $targetType,
        int    $targetId,
        array  $before = [],
        array  $after  = [],
        string $reason = ''
    ): void {
        static::create([
            'admin_id'    => auth()->id(),
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'before'      => $before ?: null,
            'after'       => $after  ?: null,
            'reason'      => $reason ?: null,
            'ip_address'  => request()->ip(),
            'created_at'  => now(),
        ]);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'user_activated'             => 'فعال‌سازی کاربر',
            'user_deactivated'           => 'غیرفعال‌سازی کاربر',
            'user_note_updated'          => 'بروزرسانی یادداشت',
            'score_override'             => 'تنظیم دستی امتیاز',
            'prediction_edited'          => 'ویرایش پیش‌بینی',
            'prediction_points_override' => 'Override امتیاز',
            'scoring_rule_created'       => 'ایجاد قانون امتیازدهی',
            'scoring_rule_updated'       => 'بروزرسانی قانون امتیازدهی',
            'bulk_activate'              => 'فعال‌سازی گروهی',
            'bulk_deactivate'            => 'غیرفعال‌سازی گروهی',
            'bulk_score_adjust'          => 'تنظیم گروهی امتیاز',
            'recalculate_scores'         => 'بازمحاسبه امتیازات',
            default                      => $this->action,
        };
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'user_activated', 'bulk_activate'                => '#00e476',
            'user_deactivated', 'bulk_deactivate'            => '#FF6B6B',
            'score_override', 'bulk_score_adjust'            => '#F59E0B',
            'prediction_edited', 'prediction_points_override' => '#4D9FFF',
            'scoring_rule_created', 'scoring_rule_updated'   => '#A78BFA',
            default                                          => '#B9CBB9',
        };
    }
}
