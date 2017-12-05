<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTFunnelChannelStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_funnel_channel_statistics', function (Blueprint $table) {
            t_field($table->increments('id'),"统计记录id");
            t_field($table->string('channel_name'),"统计渠道名称");
            t_field($table->integer('add_time'),"统计时间");
            t_field($table->integer('total_case'),"例子总量");
            t_field($table->integer('heavy_case'),"例子总量[去重]");
            t_field($table->integer('distribution_num'),"已分配销售数量");
            t_field($table->integer('tmk_effect_num'),"TMK有效数量");
            t_field($table->integer('first_phone_average'),"首次拨打平均");
            t_field($table->integer('phoned_num'),"已拨打[含拨打未接通]");
            t_field($table->integer('no_call_num'),"未拨打数量[无任何拨打记录]");
            t_field($table->integer('consumption_rate'),"消耗率[已拨打量/例子总数][比率×100除去%]");
            t_field($table->integer('called_num'),"已拨通[通话时长>0秒的电话]");
            t_field($table->integer('called_effect_num'),"已拨通-有效");
            t_field($table->integer('called_invalid_num'),"已拨通-无效");
            t_field($table->integer('called_rate'),"拨通率[已拨通量/已拨打][比率×100除去%]");
            t_field($table->integer('effect_rate'),"有效率[已拨通有效/已拨打][比率×100除去%]");
            t_field($table->integer('no_get_through_num'),"未接通[例子通话时长合计0s]");
            t_field($table->integer('no_get_through_invalid_num'),"未接通-无效[蒸汽机拨打四次均为接通，含接通30s内挂机]");
            t_field($table->integer('A_intention'),"有效意向(A)");
            t_field($table->integer('B_intention'),"有效意向(B)");
            t_field($table->integer('C_intention'),"有效意向(C)");
            t_field($table->integer('appointment_num'),"预约数[cc提交的试听申请]");
            t_field($table->integer('have_class_num'),"上课数[成功排上试听课的量]");
            t_field($table->integer('have_class_succ_nun'),"上课成功数[成功试听的量]");
            t_field($table->integer('audition_rate'),"试听率[上课成功数/已拨打量][比率×100除去%]");
            t_field($table->integer('contract_num'),"合同个数");
            t_field($table->integer('contract_people_num'),"合同人数");
            t_field($table->integer('contract_money'),"签约合同合计金额[×100]");
            $table->unique(['channel_name', 'add_time'],'channel_time_unique'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_funnel_channel_statistics');
    }
}
