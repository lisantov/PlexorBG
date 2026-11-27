<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class TitleSearch
{
    public static function applySql(Builder $query, string $search, string $column = 'title'): void
    {
        $search = trim($search);
        if ($search === '' || DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        $query->whereRaw(
            'LOWER('.$column.') LIKE ?',
            ['%'.mb_strtolower($search, 'UTF-8').'%']
        );
    }

    /**
     * @param  Collection<int, Model>  $items
     * @return Collection<int, Model>
     */
    public static function filterCollection(
        Collection $items,
        string $search,
        string $attribute = 'title',
    ): Collection {
        $search = trim($search);
        if ($search === '' || DB::connection()->getDriverName() !== 'sqlite') {
            return $items;
        }

        return $items
            ->filter(function ($model) use ($search, $attribute) {
                $title = $model->{$attribute};

                return is_string($title) && mb_stripos($title, $search, 0, 'UTF-8') !== false;
            })
            ->values();
    }
}
