<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCourseOrderAddResetLessonCountFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_course_order', function( Blueprint $table)
        {
            t_field($table->tinyInteger("reset_lesson_count_flag")->default(1),"课程是否需要每日重置检测 0 不需要 1 需要");
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
