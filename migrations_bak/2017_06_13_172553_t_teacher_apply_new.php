<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherApplyNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop("db_weiyi.t_teacher_apply");
        Schema::create('db_weiyi.t_teacher_apply', function (Blueprint $table){
            t_field($table->integer("id",true),"");
            t_field($table->integer("teacherid"),"讲师id");
            t_field($table->integer("cc_id"),"ccid");
            t_field($table->integer("lessonid"),"课程id");
            t_field($table->integer("question_type"),"问题类型");
            t_field($table->string("question_content",1024),"问题描述");
            t_field($table->integer("teacher_flag"),"讲师反馈状态1处理0未处理");
            t_field($table->integer("teacher_time"),"讲师处理反馈时间");
            t_field($table->integer("cc_flag"),"cc反馈状态1处理0未处理");
            t_field($table->integer("cc_time"),"cc处理反馈时间");
            t_field($table->integer("create_time"),"创建时间");

            $table->index("create_time");
            $table->index("lessonid");
            $table->index(["teacherid", "teacher_time" ]);
            $table->index(["cc_id","create_time"] );
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
