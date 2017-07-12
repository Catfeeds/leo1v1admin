<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddLimitPlanLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            $table->dropColumn(['limit_paln_lesson_type','limit_paln_lesson_time','limit_paln_lesson_reason','limit_paln_lesson_account']);           
        });
        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("limit_plan_lesson_type"),"0 未限制, 1 一周限排1节课 ,2 一周限排3节课 ,3 一周限排5节课" );
            t_field($table->integer("limit_plan_lesson_time"),"排课限制操作时间" );
            t_field($table->string("limit_plan_lesson_reason",5000),"排课限制原因" );
            t_field($table->string("limit_plan_lesson_account"),"排课限制操作人" );
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
