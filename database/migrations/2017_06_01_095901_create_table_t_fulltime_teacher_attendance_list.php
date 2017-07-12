<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTFulltimeTeacherAttendanceList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_fulltime_teacher_attendance_list', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("teacherid"),"全职老师");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("attendance_type"),"类型 1正常上班,2提前下班,3节假日延休");
            t_field($table->integer("attendance_time"),"休息开始日/提前下班时间");
            t_field($table->integer("day_num"),"休息天数");
            t_field($table->integer("off_time"),"提前下班时间");
            t_field($table->integer("cancel_flag"),"取消标示");
            t_field($table->string("cancel_reason"),"取消理由");              
        });

        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field($table->integer("attendance_wx_flag"),"课程取消考勤调休微信通知");
            t_field($table->integer("attendance_wx_time"),"课程取消考勤调休微信通知时间");
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
