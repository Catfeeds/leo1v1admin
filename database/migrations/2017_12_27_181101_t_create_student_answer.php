<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class TCreateStudentAnswer extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('db_question_new.t_student_answer');
        Schema::create('db_question_new.t_student_answer', function (Blueprint $table){
            $table->increments('id');
                t_field($table->integer('student_id'),"学生id");
                t_field($table->integer('teacher_id'),"教师id");
                t_field($table->integer('question_id'),"问题id");
                t_field($table->string('room_id'),"房间id");
                t_field($table->integer('step_id'),"答案id");
                t_field($table->integer('score'),"老师打分");
                t_field($table->integer('time'),"学生答题耗时");
                t_field($table->integer('create_time'),"学生答案上传时间");
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