<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TJwTeacherMonthPlanLessonInfoAddTranCountGreen extends Migration
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
            t_field($table->integer("tran_count_green"),"绿色通道转化量");
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
