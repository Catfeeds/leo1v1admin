<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentPpAgentStatusMoneyOpenFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('db_weiyi.t_agent', function( Blueprint $table)
        {
            t_field($table->integer("pp_agent_status_money")," agent 状态 上上级对应的金额 ");
            t_field($table->integer("pp_agent_status_money_open_flag"),"agent 状态 上上级 对应的金额 是否可以提现");
            t_field($table->integer("l2_agent_status_all_money"),"2级 试听 金额 ");
            t_field($table->integer("l2_agent_status_test_lesson_succ_count"),"2级 试听成功个数 ");
            t_field($table->integer("l2_agent_status_all_open_money"),"2级 可提现 试听成功金额 ");
        });
        //
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
