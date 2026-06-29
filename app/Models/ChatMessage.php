<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = ['user_id', 'body', 'is_deleted'];

    protected $casts = ['is_deleted' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
