<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTTeacherApproveReferToData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_teacher_approve_refer_to_data', function (Blueprint $table) {
            //
            t_field($table->integer("test_lesson_count") ,"总测试课次数");
            t_field($table->integer("regular_lesson_count") ,"总常规课次数");
            t_field($table->integer("no_notes_count") ,"未上传讲义次数");
            t_field($table->integer("test_lesson_later_count") ,"试听课迟到次数");
            t_field($table->integer("regular_lesson_later_count") ,"常规课迟到次数");
            t_field($table->integer("no_evaluation_count") ,"未课后评价次数");
            t_field($table->integer("turn_class_count") ,"老师调课次数");
            t_field($table->integer("ask_for_leavel_count") ,"老师请假次数");
            t_field($table->integer("test_lesson_truancy_count") ,"试听课旷课");
            t_field($table->integer("regular_lesson_truancy_count") ,"常规课旷课");
            t_field($table->integer("turn_teacher_count") ,"换老师次数");
            t_field($table->integer("stu_refund") ,"退费次数");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_teacher_approve_refer_to_data', function (Blueprint $table) {
            //
        });
    }
}
