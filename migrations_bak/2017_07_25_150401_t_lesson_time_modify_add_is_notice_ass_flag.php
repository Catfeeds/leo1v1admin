<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonTimeModifyAddIsNoticeAssFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_lesson_time_modify', function( Blueprint $table)
        {
            t_field($table->integer("is_notice_ass_flag"),"超时处理是否通知助教 0:未通知 1:已通知");
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
