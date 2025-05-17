<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;


    protected $casts = [
        'is_published' => 'boolean',
    ];


    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }


    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }


    public function scopeUnpublished($query)
    {
        return $query->where('is_published', false);
    }
}
