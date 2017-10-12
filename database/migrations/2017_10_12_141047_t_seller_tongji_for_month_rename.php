<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerTongjiForMonthRename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_seller_tongji_for_month', function( Blueprint $table)
        {

            $table->dropColumn('order_num');
            t_field($table->integer("order_cc_num"),"下单cc总人数");

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
    }
}
