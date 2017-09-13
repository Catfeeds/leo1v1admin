<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthAssStudentInfoAddRevisitTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_month_ass_student_info', function( Blueprint $table)
        {
            t_field($table->integer("revisit_target"),"回访目标量");
            t_field($table->integer("revisit_real"),"实际回访量");
            t_field($table->integer("lesson_student"),"在读学生");
            t_field($table->integer("new_student"),"新签人数");
            t_field($table->integer("new_lesson_count"),"购买课时");
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
