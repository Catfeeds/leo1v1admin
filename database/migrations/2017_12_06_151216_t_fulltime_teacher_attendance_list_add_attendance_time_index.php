<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherAttendanceListAddAttendanceTimeIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_fulltime_teacher_attendance_list', function( Blueprint $table)
        {
            $table->Index('attendance_type',"attendance_type");
            $table->Index('attendance_time',"attendance_time");
            $table->Index('adminid',"adminid");
            $table->Index(['attendance_time','adminid'],"attendance_time_adminid");
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
