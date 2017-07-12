<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTestLessonRequireTeacherList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_test_lesson_require_teacher_list', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("add_time"),"add时间");
            t_field($table->integer("require_id"),"申请id");
            t_field($table->string("teacher_info",500),"符合的老师");         
            $table->index("add_time","add_time");
            $table->index("require_id","require_id");
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
