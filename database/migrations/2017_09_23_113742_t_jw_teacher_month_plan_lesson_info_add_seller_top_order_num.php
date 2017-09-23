<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TJwTeacherMonthPlanLessonInfoAddSellerTopOrderNum extends Migration
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
            t_field($table->tinyInteger("tran_count_seller_top"),"精排转化量");                       
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
