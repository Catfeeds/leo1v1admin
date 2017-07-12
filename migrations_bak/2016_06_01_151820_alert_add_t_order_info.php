<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertAddTOrderInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_order_info', function (Blueprint $table)
        {
            $table->string("invoice");
	    $table->Integer('is_invoice');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
	Schema::table('t_order_info', function (Blueprint $table)
        {
            $table->dropColumn("invoice");
            $table->dropColumn("is_invoice");
        });
    }
}
