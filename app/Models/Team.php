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

    // ─── Flag file map (team name → public/flags filename) ───────────────────

    private static array $flagFiles = [
        'Algeria'                => 'Algeria.png',
        'Argentina'              => 'Argentina.png',
        'Australia'              => 'Australia.png',
        'Austria'                => 'Austria.png',
        'Belgium'                => 'Belgium.png',
        'Bosnia and Herzegovina' => 'Bosnia and Herzegovina.png',
        'Bosnia & Herzegovina'   => 'Bosnia and Herzegovina.png',
        'Brazil'                 => 'Brazil.png',
        'Canada'                 => 'Canada.png',
        'Cape Verde'             => 'Cape Verde.png',
        'Colombia'               => 'Colombia.png',
        'Croatia'                => 'Croatia.png',
        'Curaçao'                => 'Curaçao.png',
        'Czech Republic'         => 'Czechia.png',
        'DR Congo'               => 'DR Congo.png',
        'Ecuador'                => 'Ecuador.png',
        'Egypt'                  => 'Egypt.png',
        'England'                => 'England.png',
        'France'                 => 'France.png',
        'Germany'                => 'Germany.png',
        'Ghana'                  => 'Ghana.png',
        'Haiti'                  => 'Haiti.png',
        'Iran'                   => 'Iran.png',
        'Iraq'                   => 'Iraq.png',
        'Ivory Coast'            => 'Ivory Coast.png',
        'Japan'                  => 'Japan.png',
        'Jordan'                 => 'Jordan.png',
        'Mexico'                 => 'Mexico.png',
        'Morocco'                => 'Morocco.png',
        'Netherlands'            => 'Netherlands.png',
        'New Zealand'            => 'New Zealand.png',
        'Norway'                 => 'Norway.png',
        'Panama'                 => 'Panama.png',
        'Paraguay'               => 'Paraguay.png',
        'Portugal'               => 'Portugal.png',
        'Qatar'                  => 'Qatar.png',
        'Saudi Arabia'           => 'Saudi Arabia.png',
        'Scotland'               => 'Scotland.png',
        'Senegal'                => 'Senegal.png',
        'South Africa'           => 'South Africa.png',
        'South Korea'            => 'South Korea.png',
        'Spain'                  => 'Spain.png',
        'Sweden'                 => 'Sweden.png',
        'Switzerland'            => 'Switzerland.png',
        'Tunisia'                => 'Tunisia.png',
        'Turkey'                 => 'Turkiye.png',
        'United States'          => 'United States.png',
        'USA'                    => 'United States.png',
        'Uruguay'                => 'Uruguay.png',
        'Uzbekistan'             => 'Uzbekistan.png',
    ];

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * همیشه پرچم لوکال رو برمی‌گردونه اگر فایل وجود داشته باشه
     */
    public function getFlagUrlAttribute(?string $value): ?string
    {
        $file = self::$flagFiles[$this->name] ?? null;
        if ($file) {
            return '/flags/' . rawurlencode($file);
        }
        return $value;
    }

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
