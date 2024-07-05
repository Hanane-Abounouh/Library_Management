<?php

// app/Models/Member.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name', 'email', 'membership_date', 'status',
    ];
}

