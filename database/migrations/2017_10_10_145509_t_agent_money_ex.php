<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentMoneyEx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        //
        Schema::create('db_weiyi.t_agent_money_ex', function( Blueprint $table)
        {
            $table->increments('id'); 
            t_field($table->integer("agent_id"),"agent_id");
            t_field($table->integer("agent_money_ex_type"),"分类");
            t_field($table->integer("money"),"金额");
            t_field($table->integer("add_time"),"录入时间");
            t_field($table->integer("adminid"),"录入人");

            $table->index("add_time");
            $table->index("agent_id");
            $table->index("adminid");

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
