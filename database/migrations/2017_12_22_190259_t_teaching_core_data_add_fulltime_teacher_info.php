<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeachingCoreDataAddFulltimeTeacherInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teaching_core_data', function( Blueprint $table)
        {
            t_field($table->integer("fulltime_teacher_count"),"全职老师总人数");
            t_field($table->integer("fulltime_teacher_student"),"全职老师所带学生总数");
            t_field($table->integer("platform_teacher_count'"),"平台上课老师总人数");
            t_field($table->integer("platform_teacher_student"),"平台所有老师所带学生总数");
            t_field($table->integer("fulltime_teacher_lesson_count"),"全职老师完成的课耗总数");
            t_field($table->integer("platform_teacher_lesson_count"),"平台所有老师完成的课耗总数");
            t_field($table->integer("platform_teacher_cc_lesson"),"平台所有老师CC课程量");
            t_field($table->integer("platform_teacher_cc_order"),"平台所有老师CC签单量");
            t_field($table->integer("fulltime_teacher_cc_lesson"),"全职老师CC课程量");
            t_field($table->integer("fulltime_teacher_cc_order"),"全职老师CC签单量");
            t_field($table->integer("fulltime_normal_stu_num"),"全职老师当前所带学生数(按人次)");
            t_field($table->integer("platform_normal_stu_num"),"平台老师当前所带学生数(按人次)");

            

            
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
