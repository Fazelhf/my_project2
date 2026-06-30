<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentPrediction extends Model
{
    protected $fillable = [
        'user_id',
        'champion_team_id',
        'runner_up_team_id',
        'third_place_team_id',
        'champion_points',
        'runner_up_points',
        'third_place_points',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function champion(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'champion_team_id');
    }

    public function runnerUp(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'runner_up_team_id');
    }

    public function thirdPlace(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'third_place_team_id');
    }

    public function getTotalPointsAttribute(): int
    {
        return ($this->champion_points ?? 0) + ($this->runner_up_points ?? 0) + ($this->third_place_points ?? 0);
    }
}
