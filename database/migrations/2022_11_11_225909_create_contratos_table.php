<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('cliente_galaxpay')->nullable();
            $table->string('codigo_contrato_galaxpay');
            $table->string('plano_codigo_contrato')->nullable();
            $table->string('valor_contrato');
            $table->string('duracao_contrato')->nullable();
            $table->string('periodicidade_pagamento');
            $table->date('primeira_data_pagamento')->nullable();
            $table->string('forma_pagamento')->nullable();
            $table->string('link_pagamento')->nullable();
            $table->string('informacao_adicional')->nullable();
            $table->string('status');
            $table->foreign('cliente_galaxpay')->references('id')->on('clientes_galaxpay');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}
