<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonIntroVideoInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_lesson_intro_video_info', function( Blueprint $table)
        {
            t_field($table->integer("lessonid"),"课程id");
            t_field($table->integer("lesson_type"),"课程类型");
            t_field($table->integer("video_time"),"待播放视频的时长");
            t_field($table->integer("play_num"),"视频播放次数");
            t_field($table->integer("play_status"),"视频播放状态");
            t_field($table->integer("play_end"),"视频播放结束时间");
            $table->index("lessonid","lessonid");
            $table->index("play_end","play_end");
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
