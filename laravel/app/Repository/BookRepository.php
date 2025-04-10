<?php

namespace App\Repository;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BookRepository
{
    public function getBooks(array $params, int $itemsPerPage): LengthAwarePaginator
    {
        $query = Book::query();
        $this->mapParams($query, $params);
        return $query->paginate($itemsPerPage);
    }

    private function mapParams(Builder $query, array $params): void
    {
        foreach ($params as $key => $value) {
            $ourValue = is_array($value) ? $value[array_key_first($value)] : $value;
            $column = Str::snake($key);
            if (str_ends_with($column, '_id')) {
                $query->where($column, '=', $ourValue);
            } else {
                $query->where($column, 'LIKE', "%" . $ourValue . "%");
            }
        }
    }
}
