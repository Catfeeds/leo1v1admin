<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerTongjiForMonthAddColums extends Migration
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
            t_field($table->integer("has_tq_succ"),"已拨通数量");
            t_field($table->integer("has_tq_succ_funnel"),"已拨通数量-漏斗型");
            t_field($table->integer("has_called_funnel"),"已拨打-漏斗型");

            $table->index('create_time');
            $table->index('from_time');
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
