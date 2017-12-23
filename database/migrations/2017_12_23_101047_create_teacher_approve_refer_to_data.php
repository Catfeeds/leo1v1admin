<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherApproveReferToData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::DropIfExists("db_weiyi.t_teacher_approve_refer_to_data");
        Schema::Create("db_weiyi.t_teacher_approve_refer_to_data", function(Blueprint $table) {
            t_field($table->increments("id"), "老师晋升参考数据表");
            t_field($table->integer("teacherid"), "老师id");
            t_field($table->integer("stu_num"), "学生数");
            t_field($table->integer("total_lesson_num"), "课耗数");
            t_field($table->integer("cc_order_num"), "CC签单数");
            t_field($table->integer("cc_lesson_num"), "CC上课数");
            t_field($table->integer("cr_order_num"), "CR签单数");
            t_field($table->integer("cr_lesson_num"), "CR上课数");
            t_field($table->integer("violation_num"), "老师违规数");
            t_field($table->integer("add_time"), "添加时间");
            $table->index("add_time");
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
