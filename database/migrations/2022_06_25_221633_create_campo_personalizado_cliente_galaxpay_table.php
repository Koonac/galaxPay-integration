<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampoPersonalizadoClienteGalaxpayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campo_personalizado_cliente_galaxpay', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_galaxpay_id');
            $table->string('nome_campo_personalizado');
            $table->string('valor_campo_personalizado')->nullable();

            $table->foreign('cliente_galaxpay_id')->references('id')->on('clientes_galaxpay')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campo_personalizado_cliente_galaxpay');
    }
}
