<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherLectureInfoAddTeacherAccuracyScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_lecture_info', function( Blueprint $table)
        {         
            t_field($table->integer("teacher_accuracy_score"),"正确率得分");
            t_field($table->string("teacher_accuracy"),"正确率描述");

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
