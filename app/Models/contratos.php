<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contratos extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    function transacoes()
    {
        return $this->hasMany(transacoes_galaxpay::class, 'id_contrato');
    }
}
