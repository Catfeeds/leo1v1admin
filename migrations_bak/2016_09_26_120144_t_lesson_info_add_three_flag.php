<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddThreeFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            $table->dropColumn("rs_comment_flag");
            $table->dropColumn("rs_upload_flag");
            $table->dropColumn("rs_attend_flag");

            \App\Helper\Utils::comment_field(
                $table->integer("wx_comment_flag"), "微信通知课后评价学生 0 未通知 1 已通知 2已通知但老师未绑定微信号");
            \App\Helper\Utils::comment_field(
                $table->integer("wx_upload_flag"), "微信通知课前上传讲义 0 未通知 1 已通知 2已通知但老师未绑定微信号");
            \App\Helper\Utils::comment_field(
                $table->integer("wx_come_flag"), "微信通知上课迟到 0 未通知 1 已通知 2已通知但老师未绑定微信号");
            \App\Helper\Utils::comment_field(
                $table->integer("wx_homework_flag"), "微信通知作业未批改 0 未通知 1 已通知 2已通知但老师未绑定微信号");
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
