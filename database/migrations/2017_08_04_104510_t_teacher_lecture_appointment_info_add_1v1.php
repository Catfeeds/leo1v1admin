<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherLectureAppointmentInfoAdd1v1 extends Migration
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
            t_field($table->string("grade_1v1"),"一对一面试年级");
            t_field($table->tinyInteger("subject_1v1"),"一对一面试科目");
            t_field($table->string("trans_grade_1v1"),"一对一面试扩课年级");
            t_field($table->tinyInteger("trans_subject_1v1"),"一对一面试扩课科目");
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
