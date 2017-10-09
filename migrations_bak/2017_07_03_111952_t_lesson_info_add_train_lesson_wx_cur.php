<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddTrainLessonWxCur extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field($table->tinyInteger("train_lesson_wx_before"),"1对1面试 课前1小时发送微信通知");
            t_field($table->tinyInteger("train_lesson_wx_after"),"1对1面试 课后1小时发送微信通知");
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
