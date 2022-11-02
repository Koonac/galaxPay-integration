<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaixaFinanceiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caixa_financeiro', function (Blueprint $table) {
            $table->id();
            $table->set('status_caixa', ['A', 'F']);
            $table->unsignedBigInteger('id_user_abertura');
            $table->string('nome_user_abertura');
            $table->string('valor_abertura')->nullable();
            $table->dateTime('data_abertura');
            $table->unsignedBigInteger('id_user_fechamento')->nullable();
            $table->string('nome_user_fechamento')->nullable();
            $table->string('valor_fechamento')->nullable();
            $table->dateTime('data_fechamento')->nullable();
            $table->timestamps();

            $table->foreign('id_user_abertura')->references('id')->on('users');
            $table->foreign('id_user_fechamento')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caixa_financeiro');
    }
}
