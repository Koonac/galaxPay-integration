<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToRecebimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recebimentos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_create')->nullable()->after('observacao_recebimento');
            $table->foreign('user_create')->references('id')->on('users');
            $table->unsignedBigInteger('cliente_galaxpay_recebimento')->nullable()->after('user_create');
            $table->foreign('cliente_galaxpay_recebimento')->references('id')->on('clientes_galaxpay');
            $table->unsignedBigInteger('conta_recebimento')->nullable()->after('cliente_galaxpay_recebimento');
            $table->foreign('conta_recebimento')->references('id')->on('contas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recebimentos', function (Blueprint $table) {
            $table->dropColumn('conta_recebimento');
            $table->dropColumn('cliente_galaxpay_recebimento');
            $table->dropColumn('user_create');
        });
    }
}
