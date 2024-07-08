<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'member_id',
        'issued_date',
        'due_date',
        'returned_date',
        'fine_amount',
    ];

    // Relation avec le modèle Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relation avec le modèle Member
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
