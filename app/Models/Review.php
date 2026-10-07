<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Review extends Model
{
    public const VISIBILITY_PUBLIC  = 'public';
    public const VISIBILITY_FRIENDS = 'friends';
    public const VISIBILITY_PRIVATE = 'private';

    protected $fillable = [
        'body',
        'book_id',
        'visibility',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope: только публичные отзывы.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('visibility', self::VISIBILITY_PUBLIC);
    }

    /**
     * Scope: отзывы, видимые конкретному пользователю.
     * Включает: публичные + свои (все) + от друзей (visibility=friends).
     */
    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if (! $user) {
            return $query->where('visibility', self::VISIBILITY_PUBLIC);
        }

        $friendIds = $user->friendIds();

        return $query->where(function (Builder $q) use ($user, $friendIds) {
            $q->where('visibility', self::VISIBILITY_PUBLIC)
              ->orWhere('user_id', $user->id)
              ->orWhere(function (Builder $q2) use ($friendIds) {
                  $q2->where('visibility', self::VISIBILITY_FRIENDS)
                     ->whereIn('user_id', $friendIds);
              });
        });
    }
}