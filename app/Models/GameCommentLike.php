<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameCommentLike extends Model
{
    protected $fillable = ['comment_id', 'user_id'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(GameComment::class, 'comment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
