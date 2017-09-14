<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherSalaryList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_teacher_salary_list', function( Blueprint $table)
        {
            $table->integer("id");
            $table->integer("teacherid");
            t_field($table->integer("pay_time"),"发放工资的时间");
            t_field($table->tinyInteger("pay_status"),"发放状态");
            t_field($table->integer("all_money"),"总工资");
            t_field($table->integer("normal_base_money"),"常规课基础工资");
            t_field($table->integer("normal_reward_money"),"常规课课时奖励工资");
            t_field($table->integer("trial_money"),"试听课工资");
            t_field($table->integer("normal_lesson_total"),"常规课课时");
            t_field($table->integer("trial_lesson_total"),"试听课课时");
            t_field($table->integer("reward_money"),"其他奖励工资");
            $table->index("teacherid");
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
        Schema::drop('db_weiyi.t_teacher_salary_list');
    }
}
