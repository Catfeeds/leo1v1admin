<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonAssignTeacherAddDegree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_test_lesson_assign_teacher', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("degree"),
                "擅长程度");
        });

        //
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
