<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToDespesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->unsignedBigInteger('user_create')->nullable()->after('observacao_despesa');
            $table->foreign('user_create')->references('id')->on('users');
            $table->unsignedBigInteger('cliente_galaxpay_despesa')->nullable()->after('user_create');
            $table->foreign('cliente_galaxpay_despesa')->references('id')->on('clientes_galaxpay');
            $table->unsignedBigInteger('conta_despesa')->nullable()->after('cliente_galaxpay_despesa');
            $table->foreign('conta_despesa')->references('id')->on('contas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->dropColumn('conta_despesa');
            $table->dropColumn('cliente_galaxpay_despesa');
            $table->dropColumn('user_create');
        });
    }
}
