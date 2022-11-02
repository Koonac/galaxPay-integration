<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class caixa_financeiro extends Model
{
    use HasFactory;

    protected $table = 'caixa_financeiro';

    function recebimentos()
    {
        return $this->hasMany(recebimentos::class, 'id_caixa_financeiro');
    }

    function despesas()
    {
        return $this->hasMany(despesas::class, 'id_caixa_financeiro');
    }
}
