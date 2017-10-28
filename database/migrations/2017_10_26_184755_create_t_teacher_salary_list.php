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
            t_field($table->integer("id"),"id");
            t_field($table->integer("teacherid"),"老师id");
            t_field($table->tinyInteger("teacher_type"),"老师类型");
            t_field($table->tinyInteger("teacher_money_flag"),"老师工资发放类型");
            t_field($table->integer("lesson_1v1_money"),"1对1课工资");
            t_field($table->integer("lesson_small_money"),"小班课工资");
            t_field($table->integer("lesson_open_money"),"公开课工资");
            t_field($table->integer("reference_money"),"伯乐奖");
            t_field($table->integer("trial_train_money"),"模拟试听培训奖");
            t_field($table->integer("edit_cw_money"),"讲义编辑工资 手动录入");
            t_field($table->integer("micro_class_money"),"微课工资 手动录入");
            t_field($table->integer("agent_money"),"平台合作代理费(平台合作的抽成，非老师工资)");
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
