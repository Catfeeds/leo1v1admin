<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentCashAddCheckMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_agent_cash', function( Blueprint $table)
        {
            t_field($table->integer("check_money_flag"),"财务确认1通过2未通过");
            t_field($table->integer("check_money_adminid"),"财务审查者");
            t_field($table->integer("check_money_time"),"财务审查时间");
            t_field($table->string("check_money_desc"),"财务审查说明");
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
