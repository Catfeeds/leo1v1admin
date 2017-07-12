<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherAssessmentListAddMoralEducationScoreMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_fulltime_teacher_assessment_list', function ($table) {
            $table->dropColumn('moral_education_score_maste');
            $table->dropColumn('result_score_naster');
        });
        Schema::table('t_fulltime_teacher_assessment_list', function( Blueprint $table)
        {
            t_field($table->integer("moral_education_score_master"),"德育考评得分");            
            t_field($table->integer("result_score_master"),"成果考评得分");            
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
