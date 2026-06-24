<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_fa',
        'code',
        'group_name',
        'flag_url',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * بازی‌هایی که این تیم میزبان است
     */
    public function homeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    /**
     * بازی‌هایی که این تیم مهمان است
     */
    public function awayGames(): HasMany
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    /**
     * بازی‌هایی که این تیم به عنوان برنده نهایی (پس از پنالتی) ثبت شده
     */
    public function wonGames(): HasMany
    {
        return $this->hasMany(Game::class, 'winner_team_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /**
     * فیلتر تیم‌های یک گروه خاص
     */
    public function scopeInGroup(Builder $query, string $group): Builder
    {
        return $query->where('group_name', strtoupper($group));
    }

    /**
     * مرتب‌سازی بر اساس گروه و سپس نام
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('group_name')->orderBy('name');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * تمام بازی‌های این تیم (میزبان + مهمان) به صورت ترکیب‌شده
     */
    public function getAllGamesAttribute(): Collection
    {
        return $this->homeGames->merge($this->awayGames)->sortBy('scheduled_at');
    }

    /**
     * نام نمایشی: اگر name_fa موجود بود آن را برگردان، وگرنه name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name_fa ?? $this->name;
    }
}
