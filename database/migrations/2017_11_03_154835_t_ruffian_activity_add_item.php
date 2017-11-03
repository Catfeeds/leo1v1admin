<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRuffianActivityAddItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_ruffian_activity', function( Blueprint $table)
        {
            t_field($table->integer("create_time"),"后台奖品录入时间");
            t_field($table->integer("validity_time"),"有效期");
            t_field($table->integer("to_orderid"),"合同id");
            $table->index('to_orderid');
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
