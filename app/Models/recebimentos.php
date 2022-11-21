<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recebimentos extends Model
{
    use HasFactory;

    protected $table = 'recebimentos';

    function user()
    {
        return $this->belongsTo(User::class, 'user_create', 'id');
    }

    function galaxPayCliente()
    {
        return $this->belongsTo(clientes_galaxpay::class, 'cliente_galaxpay_recebimento', 'id');
    }

    function contaRecebimento()
    {
        return $this->belongsTo(contas::class, 'conta_recebimento', 'id');
    }
}
