<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherLectureAppointmentInfoAddAcceptAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_lecture_appointment_info', function( Blueprint $table)
        {
            t_field($table->integer("accept_adminid"),"预约试讲系统分配人id");
            t_field($table->integer("accept_time"),"预约试讲系统分配时间");
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
