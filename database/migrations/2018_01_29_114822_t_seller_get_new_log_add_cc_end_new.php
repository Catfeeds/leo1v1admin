<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerGetNewLogAddCcEndNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_seller_get_new_log', function( Blueprint $table)
        {
            t_field($table->tinyInteger("cc_end"),"拨通未满60s,挂断方0销售1客户");
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
