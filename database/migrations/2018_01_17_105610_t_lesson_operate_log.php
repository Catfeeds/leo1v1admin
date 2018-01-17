<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonOperateLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_lesson_info_operate_log', function( Blueprint $table)
        {
            t_comment($table, "课程信息操作日志");
            t_field($table->increments("id"),"id");
            t_field($table->integer("lessonid"),"课程id");
            t_field($table->string("operate_column","1000"),"操作列 例：lesson_start,lesson_end");
            t_field($table->string("operate_before","1000"),"修改前的信息 对应operate_column存放json信息 ");
            t_field($table->string("operate_after","1000"),"修改后的信息");
            t_field($table->integer("uid"),"修改人");
            t_field($table->integer("operate_time"),"修改时间");
            $table->index("lessonid","lessonid");
            $table->index("operate_time","operate_time");
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
