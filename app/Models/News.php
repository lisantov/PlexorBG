<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['author_id', 'title', 'content'])]
class News extends Model
{
    use HasUuids;

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
