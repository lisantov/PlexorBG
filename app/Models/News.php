<?php

namespace App\Models;

use App\Models\Concerns\Likeable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['author_id', 'title', 'content', 'image_path'])]
class News extends Model
{
    use HasUuids;
    use Likeable;

    protected static function booted(): void
    {
        static::deleting(function (News $news) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
        });
    }

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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Commentary::class, 'commentable');
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
