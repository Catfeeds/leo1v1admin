<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherCancelLessonListAddAddTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_cancel_lesson_list', function( Blueprint $table)
        {
            t_field($table->integer("add_time"),"添加时间" );
            $table->unique('lessonid');
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
