<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerTongjiForMonthMofigy extends Migration
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
            $table->dropColumn('seller_schedule_num_has_done');
            t_field($table->integer("seller_schedule_num_has_done_month_funnel"),"试听排课数[月到课率]-[漏斗型]-月更新");
            t_field($table->integer("seller_schedule_num_has_done_month"),"试听排课数[月到课率]");

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
