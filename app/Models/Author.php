<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;


    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
