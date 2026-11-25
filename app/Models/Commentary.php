<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['commentable_type', 'commentable_id', 'parent_id', 'user_id', 'content'])]
class Commentary extends Model
{
    use HasUuids;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
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
        return $this->hasMany(self::class, 'parent_id')->latest();
    }

    /**
     * @param  Collection<int, Commentary>  $comments
     * @return list<array<string, mixed>>
     */
    public static function toTree(Collection $comments, ?string $parentId = null): array
    {
        return $comments
            ->where('parent_id', $parentId)
            ->sortBy('created_at')
            ->values()
            ->map(fn (Commentary $comment) => [
                'id' => $comment->id,
                'content' => $comment->content,
                'author' => $comment->user?->login,
                'created_at' => $comment->created_at?->toIso8601String(),
                'children' => self::toTree($comments, $comment->id),
            ])
            ->all();
    }
}
