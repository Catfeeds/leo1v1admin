<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectReqireAddCurlStuRequestTestLessonTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_test_lesson_subject_require', function( Blueprint $table)
        {
            t_field($table->integer("curl_stu_request_test_lesson_time"),"存放期望上课时间");
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
