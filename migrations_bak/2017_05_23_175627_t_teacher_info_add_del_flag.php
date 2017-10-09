<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddDelFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("quit_time"),"离职时间");
            t_field($table->integer("leave_start_time"),"请假开始时间");
            t_field($table->integer("leave_end_time"),"请假结束时间");
            t_field($table->integer("leave_set_adminid"),"请假设置人");
            t_field($table->integer("leave_set_time"),"请假设置时间");
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
