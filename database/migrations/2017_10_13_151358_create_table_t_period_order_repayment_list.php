<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTPeriodOrderRepaymentList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        /* Schema::create('db_weiyi.t_period_order_repayment_list', function( Blueprint $table)
        {
            t_field($table->integer("child_orderid"),"子合同合同id");
            t_field($table->tinyInteger("period"),"期数");
            t_field($table->string("bid",32),"还款账户");
            t_field($table->integer("b_status"),"还款明细状态");
            t_field($table->integer("paid_time"),"已还款日期");
            t_field($table->integer("due_date"),"当期应还款日");
            t_field($table->integer("money"),"当期应还款总额");
            t_field($table->integer("paid_money"),"已还金额");
            t_field($table->integer("un_paid_money"),"未还金额");
            $table->primary(["child_orderid","period"]);
            });*/

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
