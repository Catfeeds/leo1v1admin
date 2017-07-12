<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddLateRateFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_lesson_info',function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("wx_rate_late_flag"),"微信通知超时未评价 0 未通知 1 已通知 2已通知但老师未绑定微信号");
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
