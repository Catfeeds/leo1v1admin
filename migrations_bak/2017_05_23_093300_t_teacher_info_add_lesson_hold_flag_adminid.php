<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddLessonHoldFlagAdminid extends Migration
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
            t_field($table->integer("lesson_hold_flag_adminid"),"暂停试听接课老师对应教务");
            t_field($table->integer("have_test_lesson_flag"),"是否上过试听课标识");
            t_field($table->integer("test_lesson_num"),"老师试听课总数");
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
