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
        //
        Schema::table('db_weiyi.t_teacher_salary_list', function( Blueprint $table)
        {
            t_field($table->integer("id"),"id");
            t_field($table->integer("teacherid"),"老师id");
            t_field($table->integer("teacher_type"),"老师类型");
            t_field($table->integer("teacher_money_flag"),"老师工资发放类型");
            t_field($table->integer("lesson_1v1_money"),"1v1工资");
            t_field($table->integer("lesson_small_money"),"小班课工资");
            t_field($table->integer("lesson_open_money"),"公开课工资");
            t_field($table->integer("reference_money"),"伯乐奖工资");
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
