<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'total_pages',
        'current_page',
        'cover_path',
        'file_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Процент прочитанного — считается автоматически.
     */
    public function getProgressPercentAttribute(): int
    {
        if (! $this->total_pages || $this->total_pages <= 0) {
            return 0;
        }

        return (int) round(($this->current_page / $this->total_pages) * 100);
    }
}