<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacoesGalaxpayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacoes_galaxpay', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('id_contrato')->nullable();
            $table->unsignedBigInteger('cliente_galaxpay')->nullable();
            $table->string('codigo_transacao_galaxpay');
            $table->string('valor_transacao');
            $table->date('data_pagamento_transacao');
            $table->string('status_transacao');
            $table->string('descricao_status_transacao');
            $table->string('codigo_contrato_galaxpay')->nullable();
            $table->string('link_boleto_pagamento')->nullable();
            $table->string('link_pagamento')->nullable();
            $table->foreign('id_contrato')->references('id')->on('contratos');
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
        Schema::dropIfExists('transacoes_galaxpay');
    }
}
