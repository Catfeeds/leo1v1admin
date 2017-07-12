<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}
class TLessonInfoAddLessonCancelTimeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //1 ４小时以内　２４小时以外
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            add_field($table->tinyInteger("lesson_cancel_time_type"),"课程取消时间类型 1 ４小时以内　２４小时以外");
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
