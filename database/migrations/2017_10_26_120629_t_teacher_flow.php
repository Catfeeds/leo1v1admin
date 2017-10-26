<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherFlow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("db_weiyi.t_teacher_flow", function(Blueprint $table) {
            t_field($table->integer("teacherid"), "老师id(t_teacher_info)");
            t_field($table->string("phone", 16),"老师手机");
            t_field($table->integer("answer_begin_time"), "老师报名时间");
            t_field($table->integer("trial_lecture_pass_time"),"通过试讲时间");
            t_field($table->integer("subject"),"通过试讲科目");
            t_field($table->integer("grade"),"通过试讲年级");
            t_field($table->integer("train_pass_time"),"培训通过时间");
            t_field($table->integer("simul_test_lesson_pass_time"),"模拟试听通过时间");
            t_field($table->integer("accept_adminid"),"招师专员的id(t_manage_info)");
            $table->unique("teacherid");
            $table->index('trial_lecture_pass_time');
            $table->index("train_pass_time");
            $table->index("simul_test_lesson_pass_time");
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
