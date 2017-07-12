<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddIsFreeze extends Migration
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
            t_field($table->integer("is_freeze"),"排课功能是否冻结 0 否 1 冻结" );
            t_field($table->integer("freeze_adminid"),"冻结排课操作人" );
            t_field($table->integer("freeze_time"),"冻结时间" );
            t_field($table->string("freeze_reason"),"冻结排课原因" );
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
