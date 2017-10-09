<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop("db_weiyi.t_agent_order");
        Schema::create('db_weiyi.t_agent_order', function (Blueprint $table){
            t_field($table->integer("orderid"),"订单id");
            t_field($table->integer("pid"),"上级转介绍id");
            t_field($table->integer("p_price"),"上级转介绍费");
            t_field($table->integer("ppid"),"上上级转介绍id");
            t_field($table->integer("pp_price"),"上上级转介绍费");
            t_field($table->integer("create_time"),"创建时间");

            $table->primary("orderid");
            $table->index("pid");
            $table->index("ppid");
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
