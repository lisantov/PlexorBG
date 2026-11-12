<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['solution_id', 'user_id', 'content'])]
class Commentary extends Model
{
    use HasUuids;

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class, 'solution_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
