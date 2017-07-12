<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddCancelReasonId extends Migration
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
            \App\Helper\Utils::comment_field(
                $table->integer("lesson_cancel_reason_type"),
                "课程取消 原因");
            \App\Helper\Utils::comment_field(
                $table->integer("lesson_cancel_reason_next_lesson_time"),
                "换时间 ,调整到什么时间");

        });


        //
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
