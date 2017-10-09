<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddWxSendFlag extends Migration
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
            t_field($table->tinyInteger("wx_before_four_hour_cw_flag"),"课前4小时未上传讲义微信推送");
            t_field($table->tinyInteger("wx_before_thiry_minute_remind_flag"),"课前30分钟上课提醒微信推送");
            t_field($table->tinyInteger("wx_no_comment_count_down_flag"),"试听评价倒计时15分钟还未评价微信推送");
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
