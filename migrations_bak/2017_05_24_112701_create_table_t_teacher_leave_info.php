<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTeacherLeaveInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_leave_info', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("teacherid"),"请假老师");
            t_field($table->integer("leave_start_time"),"请假开始时间");
            t_field($table->integer("leave_end_time"),"请假结束时间");
            t_field($table->integer("leave_set_adminid"),"请假设置人");
            t_field($table->integer("leave_set_time"),"请假设置时间");
            t_field($table->integer("leave_remove_adminid"),"休课解除设置人");
            t_field($table->integer("leave_remove_time"),"休课解除时间");
            t_field($table->string("leave_reason",500),"请假理由");
        });

        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("leave_remove_adminid"),"休课解除设置人");
            t_field($table->integer("leave_remove_time"),"休课解除时间");
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
