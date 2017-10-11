<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerTongjiForMonthAddColumons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_seller_tongji_for_month', function( Blueprint $table)
        {
            t_field($table->integer("seller_invit_month_funnel"),"试听邀约数-[漏斗型]-月更新");
            t_field($table->integer("has_tq_succ_invit_month_funnel"),"已拨通[月签约率]-[漏斗型]-月更新");
            t_field($table->integer("seller_plan_invit_month_funnel"),"试听排课数[月排课率]-[漏斗型]-月更新");
            t_field($table->integer("seller_test_succ_month_funnel"),"试听成功数[月到课率]-[漏斗型]-月更新");
            t_field($table->integer("order_trans_month_funnel"),"合同人数[月试听转化率]-[漏斗型]-月更新 ");

            t_field($table->integer("order_sign_month_funnel"),"合同人数[月签约率]-[漏斗型]-月更新 ");
            t_field($table->integer("order_sign_month"),"合同人数[月签约率]-存档 ");
            t_field($table->integer("has_tq_succ_sign_month_funnel"),"拨通电话数量[月签约率]-[漏斗型]-月更新");
            t_field($table->integer("has_called_stu_funnel"),"已拨打例子量[月例子消耗率]-[漏斗型]-月更新");

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
