<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publisher extends Model
{
    protected $table = "laravel_publishers";

    protected $fillable = [
        "name",
        "address"
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
