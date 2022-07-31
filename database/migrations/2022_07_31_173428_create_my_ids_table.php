<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes_galaxpay', function (Blueprint $table) {
            $table->string('meu_id')->after('codigo_cliente_galaxpay')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes_galaxpay', function (Blueprint $table) {
            $table->dropColumn('meu_id');
        });
    }
}
