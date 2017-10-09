<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoPrePayMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_order_info', function( Blueprint $table)
        {

            t_field( $table->integer("pre_price"), "定金");
            t_field( $table->integer("pre_pay_time"), "定金支付时间");
            t_field( $table->string("pre_from_orderno") ->nullable(), "定金第三方订单id");
            t_field($table->string("from_orderno") ->nullable(), "第三方订单id");

            $table->unique("pre_from_orderno");
            $table->unique("from_orderno");
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
