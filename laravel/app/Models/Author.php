<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $table = "laravel_authors";

    protected $fillable = [
        "name",
        "bio"
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return HasMany
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
