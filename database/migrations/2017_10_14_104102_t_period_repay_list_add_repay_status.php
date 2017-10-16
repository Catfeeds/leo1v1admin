<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TPeriodRepayListAddRepayStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_child_order_info', function( Blueprint $table)
        {
            t_field($table->tinyInteger("repay_status"),"还款状态(后台分析) 0,未还款;1,已还款;2,逾期已还款;3,逾期未还款");
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
