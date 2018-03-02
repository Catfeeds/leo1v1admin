<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLeaveLessonLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_leave_lesson_log', function(Blueprint $table) {
            t_comment($table, "家长请假日志表");
            t_field($table->increments("id"), "");
            t_field($table->integer("lessonid"), "课程ID");
            t_field($table->integer("parentid"), "请假人");
            t_field($table->integer("leave_time"), "请假时间");

            $table->index('lessonid');
            $table->index('parentid');
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
