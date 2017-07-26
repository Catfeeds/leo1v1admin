<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentScoreInfoAddTotalScoreSemester extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_student_score_info', function( Blueprint $table)
        {
            t_field($table->integer("semester"),"学期");
            t_field($table->integer("total_score"),"考试总分");
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
