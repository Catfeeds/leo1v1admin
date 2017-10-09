<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentAddZfb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_agent', function( Blueprint $table)
        {
            t_field($table->string("zfb_name"),"支付宝姓名");
            t_field($table->string("zfb_account"),"支付宝账户");
        });

        Schema::table('db_weiyi.t_agent_cash', function( Blueprint $table)
        {
            t_field($table->integer("type"),"提现类型1银行卡,2支付宝");
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
