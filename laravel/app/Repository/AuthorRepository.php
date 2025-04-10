<?php

namespace App\Repository;

use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AuthorRepository
{
    public function getAuthors(array $params, int $itemsPerPage): LengthAwarePaginator
    {
        $query = Author::query();
        $this->mapParams($query, $params);
        return $query->paginate($itemsPerPage);
    }

    private function mapParams(Builder $query, array $params): void
    {
        foreach ($params as $key => $value) {
            $ourValue = is_array($value) ? $value[array_key_first($value)] : $value;
            $column = Str::snake($key);
            $query->where($column, 'LIKE', "%" . $ourValue . "%");
        }
    }
}
