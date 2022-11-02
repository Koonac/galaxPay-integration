<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecebimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recebimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_caixa_financeiro');
            $table->string('valor_recebimento');
            $table->string('observacao_recebimento')->nullable();
            $table->dateTime('data_recebimento');
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
        Schema::dropIfExists('recebimentos');
    }
}
