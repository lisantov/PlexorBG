<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(key: 'id', keyType: 'string', incrementing: false, timestamps: false)]
#[Fillable(['name'])]
class Role extends Model
{
    use HasFactory;
    use HasUuids;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
