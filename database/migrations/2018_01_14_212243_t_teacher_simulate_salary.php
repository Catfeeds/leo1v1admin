<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSimulateSalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_simulate_salary_list', function( Blueprint $table)
        {
            t_comment($table, "老师模拟工资表");
            t_field($table->integer("id"),"id");
            t_field($table->integer("teacherid"),"老师id");
            t_field($table->integer("teacher_type"),"老师类型");
            t_field($table->integer("teacher_money_type"),"老师工资类型");
            t_field($table->integer("money"),"老师本月工资");
            t_field($table->integer("pay_time"),"老师工资发放时间");
            t_field($table->integer("pay_status"),"工资发放状态");
            t_field($table->integer("is_negative"),"老师工资是否为负");
            t_field($table->integer("add_time"),"工资添加时间");
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
