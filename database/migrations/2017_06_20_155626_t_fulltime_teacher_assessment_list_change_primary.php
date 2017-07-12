<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherAssessmentListChangePrimary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_fulltime_teacher_assessment_list', function( Blueprint $table)
        {
            $table->dropPrimary();
        });

        Schema::table('db_weiyi.t_fulltime_teacher_assessment_list',function( Blueprint $table)
        {
            $table->increments("id");
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
