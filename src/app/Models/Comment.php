<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'message',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
