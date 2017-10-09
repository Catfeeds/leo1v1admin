<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddTwoWeekLessonNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        // {
        //     t_field($table->integer("two_week_test_lesson_num"),"过去两周试听数");
        //     t_field($table->integer("month_stu_num"),"近一个月常规学生数");
        // });

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
