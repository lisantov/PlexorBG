<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['login', 'email', 'password_hash'])]
#[Hidden(['password_hash'])]
class User extends Authenticatable
{
    use HasFactory;
    use HasUuids;

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function requests(): MorphMany
    {
        return $this->morphMany(Request::class, 'object');
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }
}
