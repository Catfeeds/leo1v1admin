<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSalaryListDrop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists("db_weiyi.t_teacher_salary_list");
        Schema::create('db_weiyi.t_teacher_salary_list', function( Blueprint $table)
        {
            $table->integer("id",true);
            t_field($table->Integer("teacherid"),"老师id");
            t_field($table->Integer("teacher_type"),"老师类型");
            t_field($table->Integer("teacher_money_type"),"老师工资类型");
            t_field($table->Integer("money"),"老师本月工资");
            t_field($table->Integer("money_input"),"手动输入的老师工资统计");
            t_field($table->Integer("pay_time"),"老师工资发放时间");
            t_field($table->Integer("pay_status"),"老师工资发放状态 0 未发放 1 已发放");

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
