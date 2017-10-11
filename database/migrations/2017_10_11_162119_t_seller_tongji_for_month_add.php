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
            // new_order_num  //test_lesson_succ_num

            t_field($table->integer("new_order_num"),"签单数-[节点]");
            t_field($table->integer("has_tq_succ_invit_month_funnel"),"已拨通[月签约率]-[漏斗型]-月更新");

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
