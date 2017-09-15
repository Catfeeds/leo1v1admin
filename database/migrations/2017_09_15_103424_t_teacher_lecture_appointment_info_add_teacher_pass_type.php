<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherLectureAppointmentInfoAddTeacherPassType extends Migration
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
            t_field($table->integer("teacher_pass_type"),"老师入职状态标识 0,未设置;1,已入职;2,未入职");
           
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
