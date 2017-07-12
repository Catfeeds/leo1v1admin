<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherLectureAppointmentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_lecture_appointment_info', function( Blueprint $table)
        {
            t_field($table->tinyInteger("trans_subject"),"扩课科目");
        });
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("second_grade_start"),"第二科目开始年级");
            t_field($table->integer("second_grade_end"),"第二科目结束");
            t_field($table->string("second_not_grade"),"第二科目禁止年级");
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
        Schema::table('db_weiyi.t_teacher_lecture_appointment_info', function( Blueprint $table)
        {
            $table->dropColumn("trans_subject");
        });
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            $table->dropColumn("second_grade_start");
            $table->dropColumn("second_grade_end");
            $table->dropColumn("second_not_grade");
        });
    }
}
