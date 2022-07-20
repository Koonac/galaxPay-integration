<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesGalaxpayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_galaxpay', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('codigo_cliente_galaxpay');
            $table->string('nome_cliente');
            $table->string('cpf_cnpj_cliente');
            $table->string('email_cliente_1');
            $table->string('email_cliente_2')->nullable();
            $table->string('telefone_cliente_1')->nullable();
            $table->string('telefone_cliente_2')->nullable();
            $table->string('iss_nf_cliente')->nullable();
            $table->string('inscricao_municipal_cliente')->nullable();
            $table->string('status_cliente');
            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes_galaxpay');
    }
}
