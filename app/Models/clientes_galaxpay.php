<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes_galaxpay extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clientes_galaxpay';

    function enderecoClienteGalaxpay(){
        return $this->hasOne(endereco_cliente_galaxpay::class, 'cliente_galaxpay_id');
    }

    function campoPersonalizadoClienteGalaxpay(){
        return $this->hasMany(campo_personalizado_cliente_galaxpay::class, 'cliente_galaxpay_id');
    }
        
}
