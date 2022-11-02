<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_caixa_financeiro');
            $table->string('valor_despesa');
            $table->string('observacao_despesa')->nullable();
            $table->dateTime('data_despesa');
            $table->timestamps();
            $table->foreign('id_caixa_financeiro')->references('id')->on('caixa_financeiro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('despesas');
    }
}
