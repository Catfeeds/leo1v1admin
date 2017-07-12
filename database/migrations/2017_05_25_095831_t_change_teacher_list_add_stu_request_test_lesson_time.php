<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TChangeTeacherListAddStuRequestTestLessonTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_change_teacher_list', function( Blueprint $table)
        {
            t_field($table->string("stu_request_test_lesson_demand",1024),"试听需求");
            t_field($table->string("stu_request_lesson_time_info",1024),"期待常规课上课时间段");
            t_field($table->integer("stu_request_test_lesson_time"),"期望试听上课时间");
            t_field($table->integer("commend_type"),"类型 1,助教换老师申请;2,销售申请试听推荐老师");
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
