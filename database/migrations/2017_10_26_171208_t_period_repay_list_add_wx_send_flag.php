<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TPeriodRepayListAddWxSendFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_period_repay_list', function( Blueprint $table)
        {
            t_field($table->tinyInteger("warning_wx_send_flag"),"逾期预警微信通知标识");
            t_field($table->tinyInteger("stop_wx_send_flag"),"逾期停课微信通知标识");
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
