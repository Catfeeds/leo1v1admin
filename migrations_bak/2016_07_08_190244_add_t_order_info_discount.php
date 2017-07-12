<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTOrderInfoDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_order_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field($table->integer("discount_price"),"折扣价格");
            \App\Helper\Utils::comment_field($table->string("discount_reason"),"折扣原因");
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
