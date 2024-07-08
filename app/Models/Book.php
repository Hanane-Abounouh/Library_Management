<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'genre_id',
        'published_year',
        'isbn',
        'copies_available',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
