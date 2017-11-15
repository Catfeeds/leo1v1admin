<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAgentDailyLottery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_agent_daily_lottery', function (Blueprint $table) {
            t_field($table->increments('lid'),"抽奖id");
            t_field($table->integer('l_type'),"抽奖类型");
            t_field($table->integer('money'),"金额[分]");
            t_field($table->integer('agent_id'),"获得抽奖的会员id");
            t_field($table->integer('create_time'),"记录产生时间");
            $table->index(['agent_id','create_time'],'id_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_agent_daily_lotteryusers');
    }
}
