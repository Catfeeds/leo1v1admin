<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TGiftInfoAddSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_gift_info', function( Blueprint $table)
        {

            t_field($table->integer("sale"),"优惠打折，数字1-100,默认100,即不打折");

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
