<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSellerTongjiForMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_seller_tongji_for_month', function( Blueprint $table)
                {
                    $table->increments("id","id");

                    t_field($table->integer("create_time"),"创建时间");
                    t_field($table->integer("from_time"),"来自于那个月份 月份的第一天时间戳");

                    t_field($table->integer("income_referral"),"转介绍合同收入");
                    t_field($table->integer("new_money"),"新签合同收入");
                    t_field($table->text("income_info"),"对应人员收入签单额度");
                    t_field($table->integer("cc_num"),"cc入职完整月人数");
                    t_field($table->integer("order_num"),"下单总人数");
                    t_field($table->string("department_num_info"),"销售各部人数信息 json [咨询各部+新人营+培训中]");

                    t_field($table->integer("high_school_money"),"高中金额");
                    t_field($table->integer("junior_money"),"初中金额");
                    t_field($table->integer("primary_money"),"小学金额");

                    t_field($table->integer("test_invit_num"),"试听邀约数");
                    t_field($table->integer("seller_schedule_num"),"试听排课数");
                    t_field($table->integer("test_succ_num"),"试听成功数");
                    t_field($table->integer("new_order_num"),"新签合同数");

                    t_field($table->integer("seller_call_num"),"电话呼出量");
                    t_field($table->integer("has_called"),"已拨打数量");
                    t_field($table->integer("cc_called_num"),"已拨打的cc数量");
                    t_field($table->integer("new_stu"),"本月新进例子数");
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
