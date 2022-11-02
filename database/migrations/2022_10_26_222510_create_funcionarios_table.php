<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_linked_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_linked_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('acesso_clientes', ['S', 'N']);
            $table->enum('acesso_empresas', ['S', 'N']);
            $table->enum('acesso_financeiro', ['S', 'N']);
            $table->enum('acesso_galaxpay', ['S', 'N']);
            $table->enum('acesso_funcionarios', ['S', 'N']);
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
        Schema::dropIfExists('funcionarios');
    }
}
