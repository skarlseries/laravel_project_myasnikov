<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'article_id',
        'user_id',
        'body',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    // Связь с новостью
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    // Связь с пользователем
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Выборка только одобренных комментариев
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}