<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTMonthStudentCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_month_student_count', function (Blueprint $table) {
            $table->increments('id');
                t_field($table->integer("pay_stu_num"),"本月初付费学员数");
                t_field($table->integer("new_pay_stu_num"),"本月新增学员数");
                t_field($table->integer("normal_over_num"),"本月正常结课学员数");
                t_field($table->integer("refund_stu_num"),"本月退费学员数");
                t_field($table->integer("study_num"),"本月在读学员数");
                t_field($table->integer("stop_num"),"本月停课学员数");
                t_field($table->integer("drop_out_num"),"本月休学学员数");
                t_field($table->integer("vacation_num"),"本月寒暑假停课学员数");
                t_field($table->integer("has_ass_num"),"本月新签未排课合同数(已分配助教)");
                t_field($table->integer("no_ass_num"),"本月新签未排课合同数(未分配助教)");
                t_field($table->integer("warning_renow_stu_num"),"本月预警学员续费数");
                t_field($table->integer("no_warning_renow_stu_num"),"本月非预警学员续费数");
                t_field($table->integer("lesson_stu_num"),"本月上课人数");
                t_field($table->integer("lesson_count"),"本月有效课耗");
                t_field($table->integer("lesson_count_money"),"本月课耗收入");
                t_field($table->integer("create_time"),"记录时间,本月初凌晨的时间戳");
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
