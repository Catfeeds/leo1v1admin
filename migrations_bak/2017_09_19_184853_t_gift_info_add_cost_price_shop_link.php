<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TGiftInfoAddCostPriceShopLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_gift_info', function( Blueprint $table)
        {

            t_field($table->integer("cost_price"),"原价《内部可见》");
            t_field($table->string("shop_link"),"购买链接《内部可见》");

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
