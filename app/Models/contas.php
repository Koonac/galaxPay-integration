<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contas extends Model
{
    use HasFactory;

    protected $table = 'contas';

    function recebimentos()
    {
        return $this->hasMany(recebimentos::class, 'conta_recebimento');
    }

    function despesas()
    {
        return $this->hasMany(despesas::class, 'conta_despesa');
    }
}
