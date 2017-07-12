<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTrainLessonInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_train_lesson_user', function (Blueprint $table){
            t_field($table->integer("lessonid"),"培训课程id");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("userid"),"参与者id");
            $table->primary(["lessonid","userid"]);
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
