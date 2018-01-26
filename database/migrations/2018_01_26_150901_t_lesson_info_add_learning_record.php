<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddLearningRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field($table->integer("stu_check_video_time"),"学生查看视频回访时间");
            t_field($table->integer("stu_check_performance_time"),"学生查看反馈时间");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            $table->dropColumn("stu_check_video_time");
            $table->dropColumn("stu_check_performance_time");
            $table->dropColumn("stu_check_homework_time");
        });
    }
}
