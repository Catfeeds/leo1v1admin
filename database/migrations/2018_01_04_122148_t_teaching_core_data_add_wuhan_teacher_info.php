<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeachingCoreDataAddWuhanTeacherInfo extends Migration
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
            t_field($table->integer("fulltime_teacher_count_wuhan"),"武汉全职老师总人数");
            t_field($table->integer("fulltime_teacher_student_wuhan"),"武汉全职老师所带学生总数");
            t_field($table->integer("fulltime_teacher_lesson_count_wuhan"),"武汉全职老师完成的课耗总数");
            t_field($table->integer("fulltime_teacher_cc_lesson_wuhan"),"武汉全职老师CC课程量");
            t_field($table->integer("fulltime_teacher_cc_order_wuhan"),"武汉全职老师CC签单量");
            t_field($table->integer("fulltime_normal_stu_num_wuhan"),"武汉全职老师当前所带学生数(按人次)");
            t_field($table->integer("fulltime_teacher_count_shanghai"),"上海全职老师总人数");
            t_field($table->integer("fulltime_teacher_student_shanghai"),"上海全职老师所带学生总数");
            t_field($table->integer("fulltime_teacher_lesson_count_shanghai"),"上海全职老师完成的课耗总数");
            t_field($table->integer("fulltime_teacher_cc_lesson_shanghai"),"上海全职老师CC课程量");
            t_field($table->integer("fulltime_teacher_cc_order_shanghai"),"上海全职老师CC签单量");
            t_field($table->integer("fulltime_normal_stu_num_shanghai"),"上海全职老师当前所带学生数(按人次)");
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
