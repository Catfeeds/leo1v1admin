<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherFeedbackList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_teacher_feedback_list', function (Blueprint $table)
        {         
            $table->integer("id");
            $table->integer("teacherid");
            $table->integer("lessonid");
            t_field($table->integer("feedback_type"),"反馈类型");
            t_field($table->integer("status"),"反馈状态 0 未处理 1 反馈通过 2 反馈不通过");
            t_field($table->integer("lesson_count"),"反馈课时问题的补录课时");
            t_field($table->string("tea_reason",2000),"老师提交的反馈问题原因");
            t_field($table->string("back_reason",2000),"助教或系统驳回反馈的原因");
            t_field($table->integer("add_time"),"反馈提交时间");
            t_field($table->integer("check_time"),"反馈处理时间");
            t_field($table->string("sys_operator",50),"反馈操作人员");
            $table->primary("id");
            $table->index("teacherid");
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
