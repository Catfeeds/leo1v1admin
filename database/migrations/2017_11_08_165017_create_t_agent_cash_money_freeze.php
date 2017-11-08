<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAgentCashMoneyFreeze extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_agent_cash_money_freeze', function (Blueprint $table) {
            t_field($table->increments('id'),"冻结优学优享申请体现记录");
            t_field($table->integer('freeze_money'),"冻结金额[分]");
            t_field($table->integer('adminid'),"操作人");
            t_field($table->integer('create_time'),"操作时间");
            t_field($table->integer('agent_freeze_type'),"冻结类型");
            t_field($table->integer('phone'),"违规学员手机");
            t_field($table->integer('agent_money_ex_type'),"违规活动类型[agent_free_type=3时使用]");
            t_field($table->integer('agent_activity_time'),"活动时间");
            t_field($table->integer('agent_cash_id'),"申请体现id");
            $table->index('agent_cash_id');
            $table->index('adminid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_agent_cash_money_freezeusers');
    }
}
