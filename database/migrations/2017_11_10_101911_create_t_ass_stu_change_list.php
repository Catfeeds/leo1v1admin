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
            t_field($table->text("registered_student_list"),"在册学员名单");
            t_field($table->integer('end_no_renw_num'),"结课未续费人数");
            t_field($table->integer('estimate_month_lesson_count'),"预估月课时消耗总量");
            t_field($table->integer('seller_month_lesson_count'),"销售月生产总课时");
            t_field($table->string('seller_week_stu_num',32),"销售月周学生数");
            t_field($table->integer('ass_refund_money'),"助教责任退费金额");
            t_field($table->integer('all_ass_stu_num'),"助教所有学员数量");
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
