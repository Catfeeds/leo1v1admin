<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentOrderAddOpenPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_agent_order', function( Blueprint $table)
        {
            t_field($table->integer("p_open_price"),"上级可提现金额");
            t_field($table->integer("pp_open_price"),"上上级可提现金额");
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
