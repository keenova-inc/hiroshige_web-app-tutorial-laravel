<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'content', 'username',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Article $article) {
            $article->comments()->delete();
        });
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
