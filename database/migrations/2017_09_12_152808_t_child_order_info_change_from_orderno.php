<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TChildOrderInfoChangeFromOrderno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_child_order_info', function( Blueprint $table)
        {
            $table->dropColumn("from_orderno");
        });
        Schema::table('db_weiyi.t_child_order_info', function( Blueprint $table)
        {
            t_field($table->string("from_orderno")->default(null),"第三方订单");
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
