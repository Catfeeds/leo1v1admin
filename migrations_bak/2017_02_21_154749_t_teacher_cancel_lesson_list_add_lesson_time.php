<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherCancelLessonListAddLessonTime extends Migration
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
            $table->dropColumn("add_time");
            t_field($table->integer("lesson_time"),"课程时间" );
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
