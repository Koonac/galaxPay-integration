<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWebhookHashToGalaxpayParametros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaxpay_parametros', function (Blueprint $table) {
            $table->string('webhook_hash')->nullable()->after('galax_token');
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
            $table->dropColumn('webhook_hash');
        });
    }
}
