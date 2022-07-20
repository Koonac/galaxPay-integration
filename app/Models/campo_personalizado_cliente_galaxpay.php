<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class campo_personalizado_cliente_galaxpay extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campo_personalizado_cliente_galaxpay';

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cliente_galaxpay_id',
        'nome_campo_personalizado',
        'valor_campo_personalizado',
    ];
}
