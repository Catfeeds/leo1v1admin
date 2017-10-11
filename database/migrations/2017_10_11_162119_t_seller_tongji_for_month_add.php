<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerTongjiForMonthAdd extends Migration
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

            t_field($table->integer("new_order_num"),"签单数-[节点]");

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
