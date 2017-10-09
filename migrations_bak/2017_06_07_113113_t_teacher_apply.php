<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherApply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_apply', function (Blueprint $table){
            t_field($table->integer("id"),"");
            t_field($table->integer("teacher_id"),"讲师id");
            t_field($table->integer("cc_id"),"ccid");
            t_field($table->integer("lesson_id"),"课程id");
            t_field($table->integer("question_type"),"问题类型");
            t_field($table->string("question_content",128)->nullable(),"问题描述");
            t_field($table->integer("teacher_flag"),"讲师反馈状态1处理0未处理");
            t_field($table->integer("teacher_time"),"讲师处理反馈时间");
            t_field($table->integer("cc_flag"),"cc反馈状态1处理0未处理");
            t_field($table->integer("cc_time"),"cc处理反馈时间");
            t_field($table->integer("create_time"),"创建时间");

            $table->primary("id");
            $table->index("lesson_id");
            $table->index("teacher_id");
            $table->index("teacher_time");
            $table->index("teacher_flag");
            $table->index("cc_id");
            $table->index("cc_time");
            $table->index("cc_flag");
            $table->index("create_time");
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
