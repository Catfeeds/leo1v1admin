<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherAssessmentListAddActivePartScoreNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_fulltime_teacher_positive_require_list', function ($table) {
            $table->dropColumn('active_part_score');
        });
        Schema::table('db_weiyi.t_fulltime_teacher_assessment_list', function( Blueprint $table)
        {
            t_field($table->integer("active_part_score"),"积极参与");            
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
