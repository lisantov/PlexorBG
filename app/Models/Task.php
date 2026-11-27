<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Models\Concerns\Likeable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['user_id', 'title', 'description', 'content', 'reference_code', 'answer', 'status'])]
class Task extends Model
{
    use HasUuids;
    use Likeable;

    public function requests(): MorphMany
    {
        return $this->morphMany(Request::class, 'object');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class);
    }

    public function solutionSessions(): HasMany
    {
        return $this->hasMany(SolutionSession::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Commentary::class, 'commentable');
    }

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }
}
