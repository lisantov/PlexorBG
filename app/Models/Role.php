<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Fillable(['name', 'access_codes'])]
class Role extends Model
{
    use HasUuids;

    public function requests(): MorphMany {
        return $this->morphMany(Request::class, 'object');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'access_codes' => 'array',
        ];
    }
}
