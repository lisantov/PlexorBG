<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['user_id', 'task_id', 'session_id', 'code'])]
class Solution extends Model
{
    use HasUuids;

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
}
