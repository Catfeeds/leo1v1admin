<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoLessonCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::table('t_student_info', function( Blueprint $table)
        {
            $table->integer("lesson_count_all")->comment("签约课时数"); 
            $table->integer("lesson_count_left")->comment("剩余课时数"); 
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_student_info', function( Blueprint $table)
        {
            $table->dropColumn("lesson_count_all");
            $table->dropColumn("lesson_count_left");
        });
        //
    }
}
