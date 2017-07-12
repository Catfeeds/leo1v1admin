<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonAssignTeacherList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	   Schema::create('t_test_lesson_assign_teacher', function (Blueprint $table)
       {
           $table->integer("id",true);
           \App\Helper\Utils::comment_field(
               $table->integer("seller_student_id"),"from t_seller_student_info"); 
           $table->integer("teacherid");
           \App\Helper\Utils::comment_field(
           $table->integer("assign_time")
           ,"派单时间");
           \App\Helper\Utils::comment_field(
               $table->integer("teacher_confirm_flag"),"接受flag, 0:未设置,1:接受,2:不接受");
           $table->integer("teacher_confirm_time");
           $table->index(["seller_student_id"]);
           $table->index(["teacherid", "assign_time" ]);
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
