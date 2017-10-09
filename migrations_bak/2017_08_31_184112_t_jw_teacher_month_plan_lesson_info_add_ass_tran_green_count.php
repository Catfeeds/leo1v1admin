<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TJwTeacherMonthPlanLessonInfoAddAssTranGreenCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_jw_teacher_month_plan_lesson_info', function( Blueprint $table)
        {
            t_field($table->integer("ass_tran_green_count"),"助教绿色通道转化量");
            t_field($table->integer("seller_tran_green_count"),"销售绿色通道转化量");
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
