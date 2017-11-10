<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAssStuChangeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_ass_stu_change_list', function (Blueprint $table) {
            t_field($table->increments('id'),"学生助教信息变更记录表");
            t_field($table->integer('add_time'),"变更时间");
            t_field($table->integer('userid'),"学生");
            t_field($table->integer('assistantid'),"助教id");
            t_field($table->integer('adminid'),"助教后台帐号");
            t_field($table->integer('assign_ass_time'),"学生分配给该助教的时间");
            $table->index('add_time');
            $table->index('assign_ass_time');
            $table->index('adminid');
            $table->index('userid');
        });

        Schema::table('db_weiyi.t_month_ass_student_info', function( Blueprint $table)
        {
            t_field($table->text("stop_student_list"),"停课学员名单");
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
