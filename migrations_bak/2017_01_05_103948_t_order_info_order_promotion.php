<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoOrderPromotion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_order_info', function( Blueprint $table)
        {
            t_field($table->integer("order_price_type"),"促销id" );
            t_field($table->integer("order_promotion_type"),"促销分类" );
            t_field($table->integer("promotion_discount_price"),"折扣后价格*100" );
            t_field($table->integer("promotion_present_lesson"),"赠送*100" );
            t_field($table->integer("promotion_spec_discount"),"特殊折扣后价格*100" );
            t_field($table->integer("promotion_spec_present_lesson"),"特殊赠送*100" );
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
