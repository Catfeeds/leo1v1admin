<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentEditBankcardIdcardType extends Migration
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
            t_field($table->dropColumn("bankcard"),"银行卡号");
            t_field($table->dropColumn("idcard"),"身份证号码");
        });

        Schema::table('db_weiyi.t_agent', function( Blueprint $table)
        {
            t_field($table->string("bankcard"),"银行卡号");
            t_field($table->string("idcard"),"身份证号码");
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
