<?php

namespace App\Models;

use App\Models\Concerns\Likeable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['user_id', 'task_id', 'session_id', 'code'])]
class Solution extends Model
{
    use HasUuids;
    use Likeable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function solutionSession(): BelongsTo
    {
        return $this->belongsTo(SolutionSession::class, 'session_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Commentary::class, 'commentable');
    }
}
