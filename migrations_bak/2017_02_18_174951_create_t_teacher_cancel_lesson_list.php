<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherCancelLessonList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_teacher_cancel_lesson_list', function (Blueprint $table){
            t_field($table->integer("teacherid"),"老师id");
            t_field($table->integer("cancel_time"),"取消时间");
            t_field($table->integer("lessonid"),"课程id");
            t_field($table->string("account"),"操作人");
            $table->primary(["teacherid","cancel_time"]);
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
