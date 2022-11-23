<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposJurosToContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->string('percentual_multa')->nullable();
            $table->string('percentual_juros')->nullable();
            $table->string('observacao_boleto')->nullable();
            $table->integer('qtde_pagamento_pos_vencimento')->nullable();
            $table->enum('aplicar_desconto', ['N', 'S'])->nullable();
            $table->enum('tipo_desconto', ['F', 'P'])->comment('F = valor fixo, P = valor percentual')->nullable();
            $table->integer('qtde_dias_validade_desconto')->nullable();
            $table->string('valor_desconto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn('percentual_multa');
            $table->dropColumn('percentual_juros');
            $table->dropColumn('observacao_boleto');
            $table->dropColumn('qtde_pagamento_pos_vencimento');
            $table->dropColumn('aplicar_desconto');
            $table->dropColumn('tipo_desconto');
            $table->dropColumn('qtde_dias_validade_desconto');
            $table->dropColumn('valor_desconto');
        });
    }
}
