<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherRecordListAddTeacherDetailScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_record_list', function( Blueprint $table)
        {
            t_field($table->string("teacher_detail_score"),"成绩详情");
            t_field($table->integer("teacher_lecture_score"),"成绩");
            t_field($table->integer("work_year"),"工作年限");
            t_field($table->string("sshd_good"),"老师标签");
            t_field($table->string("not_grade"),"禁止年级");
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
