<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAddLastLessonTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_student_info', function( Blueprint $table)
        {
            
            \App\Helper\Utils::comment_field(
                $table->unsignedInteger("last_lesson_time"), //j
                "最后一次上课时间"
                );
            $table->index("last_lesson_time");
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
            $table->dropIndex("last_lesson_time");
            $table->dropColumn("last_lesson_time");
        });	

        //
    }
}
