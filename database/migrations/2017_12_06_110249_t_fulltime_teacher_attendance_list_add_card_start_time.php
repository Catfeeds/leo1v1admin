<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherAttendanceListAddCardStartTime extends Migration
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
            t_field($table->integer("card_start_time"), "打卡开始时间");
            t_field($table->integer("card_end_time"), "打卡结束时间");
            t_field($table->tinyInteger("leave_type"), "请假类型");
            t_field($table->text("leave_custom"), "请假说明");
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
