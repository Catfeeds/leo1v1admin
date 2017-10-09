<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthAssStudentInfoAddLessonTotalOld extends Migration
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
            t_field($table->integer("lesson_total_old"),"月有效课时数");
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
