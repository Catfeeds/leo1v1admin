<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAlterIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            $table->dropIndex("grade_limit");
            $table->Index(['subject','grade_start','grade_end'],"subject_limit");
            $table->Index(['second_subject','second_grade_start','second_grade_end'],"second_subject_limit");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            $table->Index(['grade_start','grade_end'],"grade_limit");
            $table->dropIndex("subject_limit");
            $table->dropIndex("second_subject_limit");
        });
    }
}
