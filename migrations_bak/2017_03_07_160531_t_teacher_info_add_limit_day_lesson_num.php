<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddLimitDayLessonNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("limit_day_lesson_num")->default(4),"老师每日最大排课数量");
            t_field($table->integer("limit_week_lesson_num")->default(8),"老师每周最大排课数量");
            t_field($table->integer("limit_month_lesson_num")->default(30),"老师每月最大排课数量");
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
