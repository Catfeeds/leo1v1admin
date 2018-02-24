<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentTestPaper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi.t_student_test_paper');
        Schema::create('db_weiyi.t_student_test_paper', function(Blueprint $table) {
            t_comment($table, "学生评测试卷");

            t_field($table->increments("paper_id"), "id");
            t_field($table->string("paper_name"), "试卷名字");
            t_field($table->integer("subject"),"科目id");
            t_field($table->integer("grade"),"年级id");
            t_field($table->integer("book"),"教材id");
            t_field($table->integer("volume"),"上下册");
            t_field($table->integer("status"),"状态");
            t_field($table->integer("adminid"),"编辑人");
            t_field($table->integer("modify_time"),"编辑时间");
            t_field($table->string("answer",4096),"答案");
            t_field($table->string("dimension",4096),"维度");
            t_field($table->string("question_bind",4096),"维度");
            t_field($table->string("suggestion",4096),"建议");
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
