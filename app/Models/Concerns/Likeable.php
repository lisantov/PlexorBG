<?php

namespace App\Models\Concerns;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likeable
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithLikeMeta(Builder $query, ?User $user): Builder
    {
        $query->withCount('likes');

        if ($user) {
            $query->withExists([
                'likes as liked_by_user' => fn ($q) => $q->where('user_id', $user->id),
            ]);
        }

        return $query;
    }

    /**
     * @return array{likes_count: int, liked: bool}
     */
    public function likeProps(): array
    {
        return [
            'likes_count' => (int) ($this->likes_count ?? 0),
            'liked' => (bool) ($this->liked_by_user ?? false),
        ];
    }
}
