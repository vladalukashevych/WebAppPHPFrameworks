<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = "laravel_books";

    protected $fillable = [
        "title",
        "publication_year",
        "author_id",
        "genre_id",
        "publisher_id"
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return BelongsTo
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * @return BelongsTo
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * @return HasMany
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
