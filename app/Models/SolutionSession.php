<?php

namespace App\Models;

use App\Enums\SolutionSessionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['task_id', 'status', 'payload'])]
class SolutionSession extends Model
{
    use HasUuids;

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class, 'session_id');
    }

    public function casts(): array
    {
        return [
            'status' => SolutionSessionStatus::class,
        ];
    }
}
