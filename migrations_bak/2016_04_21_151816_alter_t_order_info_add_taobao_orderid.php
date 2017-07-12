<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTOrderInfoAddTaobaoOrderid extends Migration
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
            $table->string("taobao_orderid",50);
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
            $table->dropColumn("taobao_orderid");
        });
    }
}
