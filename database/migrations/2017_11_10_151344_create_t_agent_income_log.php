<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAgentIncomeLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_agent_income_log', function (Blueprint $table) {
            t_field($table->increments('logid'),"日志id");
            t_field($table->integer('agent_income_type'),"收入类型");
            t_field($table->integer('money'),"金额[分]");
            t_field($table->integer('agent_id'),"获得收入的会员id");
            t_field($table->integer('child_agent_id'),"邀请会员的会员id[活动奖励时该记录为空]");
            t_field($table->integer('create_time'),"记录产生时间");
            $table->index(['agent_id','create_time'],'id_time');
            $table->index('child_agent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_agent_income_log');
    }
}
