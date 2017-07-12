<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TGraduatingStudentLessonTimeCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_graduating_student_lesson_time_count', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("userid"),"用户id");
            t_field($table->integer("start_time"),"起始时间");
            t_field($table->integer("plan_lesson_time"),"计划课时");
            $table->unique( [ 'userid', 'start_time' ], "u_opt_time");
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
