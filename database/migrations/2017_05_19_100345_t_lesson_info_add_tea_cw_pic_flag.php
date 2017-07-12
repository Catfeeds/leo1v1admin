<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddTeaCwPicFlag extends Migration
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
            t_field($table->tinyInteger("tea_cw_pic_flag"),"老师讲义是否转化为图片 0 不转化 1 转化");
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
