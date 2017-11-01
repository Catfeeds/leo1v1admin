<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAgentGroupMemberResult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_agent_group_member_result', function (Blueprint $table) {
            t_field($table->increments('id'),"团队成员业绩统计表");
            t_field($table->integer('agent_id'),"agent表对应id");
            t_field($table->string('create_time'),"生成时间");
            t_field($table->integer('cycle_student_count'),"学员量[无下限限制下级]");
            t_field($table->integer('cycle_test_lesson_count'),"试听量[无下限限制下级]");
            t_field($table->integer('cycle_order_money'),"签单金额[无下限限制下级][分]");
            t_field($table->integer('cycle_member_count'),"会员量[无下限限制下级]");
            t_field($table->integer('cycle_order_count'),"签单量[无下限限制下级]");
            $table->index('agent_id');
            $table->index('create_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_agent_group_member_result');
    }
}
