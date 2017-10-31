<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSalaryAddPayColumn extends Migration
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
            t_field($table->integer("pay_time"),"发工资时间");
            t_field($table->integer("pay_status"),"发工资状态 0 未发 1 已发");
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
