<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefreshTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaxpay_parametros', function (Blueprint $table) {
            $table->dateTime('refresh_token')->after('galax_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galaxpay_parametros', function (Blueprint $table) {
            $table->dropColumn('refresh_token');
        });
    }
}
