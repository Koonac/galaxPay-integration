<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class despesas extends Model
{
    use HasFactory;

    protected $table = 'despesas';

    function user()
    {
        return $this->belongsTo(User::class, 'user_create', 'id');
    }
}
