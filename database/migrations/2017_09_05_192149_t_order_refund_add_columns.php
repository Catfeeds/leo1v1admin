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
            t_field($table->integer("qc_contact_status"),"联系状态");
            t_field($table->integer("qc_advances_status"),"提升状态");
            t_field($table->integer("qc_voluntarily_status"),"态度情况");
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
