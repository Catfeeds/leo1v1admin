<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderRefundResetColunm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::table('db_weiyi.t_order_refund', function( Blueprint $table)
        {

            $table->dropColumn('free_time');
            $table->dropColumn('qc_deal_time');
            t_field($table->integer("qc_deal_time"),"QC处理时间");
        });

        //
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
