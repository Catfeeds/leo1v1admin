<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTChannelNodeTypeStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_channel_node_type_statistics', function (Blueprint $table) {
            t_field($table->increments('id'),"统计记录id");
            t_field($table->string('channel_name'),"统计渠道名称");
            t_field($table->integer('add_time'),"统计时间");
            t_field($table->integer('all_count'),"例子总量");
            t_field($table->integer('heavy_count'),"例子总量[去重]");
            t_field($table->integer('assigned_count'),"已分配销售数量");
            t_field($table->integer('tmk_assigned_count'),"TMK有效数量");
            t_field($table->integer('avg_first_time'),"首次拨打平均");
            t_field($table->integer('tq_called_count'),"已拨打[含拨打未接通]");
            t_field($table->integer('tq_no_call_count'),"未拨打数量[无任何拨打记录]");
            t_field($table->integer('consumption_rate'),"消耗率[已拨打量/例子总数][比率除去%]");
            t_field($table->integer('called_num'),"已拨通[通话时长>0秒的电话]");
            t_field($table->integer('tq_call_succ_valid_count'),"已拨通-有效");
            t_field($table->integer('tq_call_succ_invalid_count'),"已拨通-无效");
            t_field($table->integer('called_rate'),"拨通率[已拨通量/已拨打][比率除去%]");
            t_field($table->integer('effect_rate'),"有效率[已拨通有效/已拨打][比率除去%]");
            t_field($table->integer('tq_call_fail_count'),"未接通[例子通话时长合计0s]");
            t_field($table->integer('tq_call_fail_invalid_count'),"未接通-无效[蒸汽机拨打四次均为接通，含接通30s内挂机]");
            t_field($table->integer('have_intention_a_count'),"有效意向(A)");
            t_field($table->integer('have_intention_b_count'),"有效意向(B)");
            t_field($table->integer('have_intention_c_count'),"有效意向(C)");
            t_field($table->integer('require_count'),"预约数[cc提交的试听申请]");
            t_field($table->integer('test_lesson_count'),"上课数[成功排上试听课的量]");
            t_field($table->integer('succ_test_lesson_count'),"上课成功数[成功试听的量]");
            t_field($table->integer('audition_rate'),"试听率[上课成功数/已拨打量][比率除去%]");
            t_field($table->integer('order_count'),"合同个数");
            t_field($table->integer('user_count'),"合同人数");
            t_field($table->integer('order_all_money'),"签约合同合计金额[×100]");
            t_field($table->integer('distinct_succ_count'),"试听成功去重个数");
            t_field($table->integer('distinct_test_count'),"试听申请个数去重");
            t_field($table->string('key0'),"渠道key0");
            t_field($table->string('key1'),"渠道key1");
            t_field($table->string('key2'),"渠道key2");
            t_field($table->string('key3'),"渠道key3");
            t_field($table->string('key4'),"渠道key4");
            t_field($table->string('key0_class'),"层次排序字段0");
            t_field($table->string('key1_class'),"层次排序字段1");
            t_field($table->string('key2_class'),"层次排序字段2");
            t_field($table->string('key3_class'),"层次排序字段3");
            t_field($table->string('key4_class'),"层次排序字段4");
            t_field($table->string('old_key5'),"上级key");
            t_field($table->string('level'),"层次层级");
            t_field($table->integer('sort'),"排序");
            $table->index(['channel_name', 'add_time'],'channel_time_unique'); 
            $table->index('add_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_channel_node_type_statistics');
    }
}
