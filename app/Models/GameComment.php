<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameComment extends Model
{
    protected $fillable = ['game_id', 'user_id', 'parent_id', 'body', 'is_deleted'];

    protected $casts = ['is_deleted' => 'boolean'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->with('user', 'likes')->orderBy('created_at');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(GameCommentLike::class, 'comment_id');
    }

    public function isLikedBy(int $userId): bool
    {
        return $this->likes->where('user_id', $userId)->isNotEmpty();
    }
}
