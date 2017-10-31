<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthStudentCountAddPayOrderNumRefundOrderNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_month_student_count', function( Blueprint $table)
        {
            t_field($table->integer("pay_order_num"),"月初订单总数");
            t_field($table->integer("refund_order_num"),"月末退费订单数");
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
