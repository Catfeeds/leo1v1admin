<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TChangeStudentTestAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi.t_student_test_answer');
        Schema::create('db_weiyi.t_student_test_answer', function(Blueprint $table) {
            t_comment($table, "学生评测试卷答案");

            t_field($table->increments("id"), "id");
            t_field($table->integer("userid"), "userid");
            t_field($table->string("phone"), "手机号");
            t_field($table->integer("paper_id"), "试卷id");
            t_field($table->integer("time_token"), "试卷所花时间");
            t_field($table->integer("submittime"),"答案提交时间");
            t_field($table->string("student_answers",4096),"提交答案");
            t_field($table->string("student_scores",4096),"每题得分");
            t_field($table->string("dimension_scores",4096),"每个维度得分");
            t_field($table->string("dimension_suggest",4096),"维度建议");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
