<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectAddStuRequestTestLessonTimeEnd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_test_lesson_subject', function( Blueprint $table)
        {
            t_field($table->integer("stu_request_test_lesson_time_end"),"期望上课时间最晚时间");
            $table->index("stu_request_test_lesson_time_end",'end_time');
        });
        Schema::table('db_weiyi.t_test_lesson_subject_require', function( Blueprint $table)
        {
            t_field($table->integer("curl_stu_request_test_lesson_time_end"),"期望上课时间最晚时间");
            $table->index("curl_stu_request_test_lesson_time_end",'end_time');
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
