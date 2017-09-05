<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderRefundAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_order_refund', function( Blueprint $table)
        {
            // t_field($table->string("qc_other_reason",1000),"qc其他原因");
            // t_field($table->string("qc_analysia",1000),"qc整体分析");
            // t_field($table->string("qc_reply",1000),"qc 应对方案");
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
