<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerTongjiForMonthReset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('t_seller_tongji_for_month');

        Schema::create('db_weiyi.t_seller_tongji_for_month', function( Blueprint $table)
        {
            $table->increments("id","id");
            t_field($table->integer("create_time"),"创建时间");
            t_field($table->integer("from_time"),"来自于那个月份 或者 周 第一天时间戳");
            t_field($table->integer("referral_money"),"转介绍合同收入");
            t_field($table->integer("new_money"),"新签合同收入");
            t_field($table->integer("order_num"),"下单总人数");
            t_field($table->integer("formal_info"),"入职完整月人员收入");
            t_field($table->integer("formal_num"),"入职完整月人员人数");
            t_field($table->integer("seller_target_income"),"销售目标收入");
            t_field($table->integer("total_num"),"销售合同量");
            t_field($table->integer("one_department"),"销售一部人数");
            t_field($table->integer("two_department"),"销售二部人数");
            t_field($table->integer("three_department"),"销售三部人数");
            t_field($table->integer("new_department"),"销售新人营人数");
            t_field($table->integer("train_department"),"销售培训中");
            t_field($table->integer("referral_money"),"转介绍金额");
            t_field($table->integer("high_school_money"),"高中金额");
            t_field($table->integer("junior_money"),"初中金额");
            t_field($table->integer("primary_money"),"小学金额");



            t_field($table->integer("seller_invit_num"),"试听邀约数");
            t_field($table->integer("seller_schedule_num"),"试听排课数");
            t_field($table->integer("test_succ_num"),"试听成功数");
            t_field($table->integer("seller_invit_month"),"拨通电话数量[月签约率]");
            t_field($table->integer("has_tq_succ_invit_month"),"已拨通[月签约率]");
            t_field($table->integer("seller_plan_invit_month"),"试听排课数[月排课率]");
            t_field($table->integer("seller_test_succ_month"),"试听成功数[月到课率]");
            t_field($table->integer("order_trans_month"),"合同人数[月试听转化率]");
            t_field($table->integer("has_tq_succ_sign_month"),"拨通电话数量[月签约率]");
            t_field($table->integer("has_tq_succ_sign_month"),"拨通电话数量[月签约率]");


            t_field($table->integer("seller_call_num"),"电话呼出量");
            t_field($table->integer("has_called"),"已拨打数量");
            t_field($table->integer("has_tq_succ"),"已拨通数量[接通率]");
            t_field($table->integer("claim_num"),"已认领[认领率]");
            t_field($table->integer("new_stu"),"本月新进例子数");
            t_field($table->integer("has_called_stu"),"已拨打例子量[月例子消耗率]");

            t_field($table->integer("cc_called_num"),"拨打的cc量");
            t_field($table->integer("cc_call_time"),"cc总计通话时长");

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
