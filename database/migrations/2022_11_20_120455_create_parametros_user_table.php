<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametrosUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametros_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->string('valor_card');
            $table->string('valor_cancelamento_contrato');
            $table->integer('cobrar_cancelamento_meses');
            $table->unsignedBigInteger('conta_recebimento_padrao')->nullable();
            $table->integer('quantidade_dependentes_galaxpay')->nullable();
            $table->string('nome_campo_dependente')->nullable();
            $table->string('cpf_campo_dependente')->nullable();
            $table->string('nascimento_campo_dependente')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('conta_recebimento_padrao')->references('id')->on('contas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parametros_user');
    }
}
