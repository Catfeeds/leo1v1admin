<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddIsWeekFreeze extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("is_week_freeze"),"一周排课功能是否冻结 0 否 1 冻结" );
            t_field($table->integer("week_freeze_adminid"),"一周冻结排课操作人" );
            t_field($table->integer("week_freeze_time"),"一周冻结时间" );
            t_field($table->string("week_freeze_reason"),"冻结排课一周原因" );
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
