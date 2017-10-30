<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerTongjiForMonthAddItem extends Migration
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
            t_field($table->integer("seller_schedule_num_month"),"试听排课数 [月排课数]");
            t_field($table->integer("seller_schedule_num_month_funnel"),"试听排课数 [月排课率]-[漏斗型]-月更新");
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
