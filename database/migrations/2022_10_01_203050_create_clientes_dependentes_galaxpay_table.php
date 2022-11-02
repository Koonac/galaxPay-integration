<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesDependentesGalaxpayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_dependentes_galaxpay', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_galaxpay_id');
            $table->string('nome_cliente_dependente');
            $table->string('cpf_cliente_dependente')->unique();
            $table->string('nascimento_cliente_dependente');
            $table->string('matricula_cliente_dependente');
            $table->timestamps();
            $table->foreign('cliente_galaxpay_id')->references('id')->on('clientes_galaxpay')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes_dependentes_galaxpay');
    }
}
