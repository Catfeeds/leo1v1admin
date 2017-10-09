<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTPsychologicalTeacherTimeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_psychological_teacher_time_list', function (Blueprint $table){
            t_field($table->integer("day"),"日期");
            t_field($table->string("start",64),"开始时间");
            t_field($table->string("end",64),"结束时间");
            t_field($table->string("teacher_phone_list",512),"老师手机信息");           
            $table->primary(["day","start"]);
        });

        Schema::table('db_weiyi.t_teacher_lecture_appointment_info', function( Blueprint $table)
        {
            t_field($table->integer("lecture_revisit_type"),"试讲例子回访状态");
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
