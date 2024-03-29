<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataRecebimentoTransacaoToTransacoesGalaxpay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transacoes_galaxpay', function (Blueprint $table) {
            $table->dateTime('data_recebimento_transacao')->nullable()->after('data_pagamento_transacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transacoes_galaxpay', function (Blueprint $table) {
            $table->dropColumn('data_recebimento_transacao');
        });
    }
}
