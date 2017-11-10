<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAssStuChangeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_ass_stu_change_list', function (Blueprint $table) {
            t_field($table->increments('id'),"学生助教信息变更记录表");
            t_field($table->integer('add_time'),"变更时间");
            t_field($table->integer('userid'),"学生");
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
        //
    }
}
