<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentOrderAddPLevelPpLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_agent_order', function( Blueprint $table){
            t_field($table->integer("p_level"),"订单产生时上级当前等级");
            t_field($table->integer("pp_level"),"订单产生时上上级当前等级");
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
