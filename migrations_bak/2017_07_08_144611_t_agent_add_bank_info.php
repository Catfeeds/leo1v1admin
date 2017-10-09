<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentAddBankInfo extends Migration
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
            t_field($table->integer("bankcard"),"银行卡号");
            t_field($table->integer("idcard"),"身份证号码");
            t_field($table->string("bank_address"),"开户行和支行");
            t_field($table->string("bank_account"),"持卡人姓名");
            t_field($table->string("bank_phone"),"银行预留手机号");
            t_field($table->string("bank_province"),"银行卡开户省");
            t_field($table->string("bank_city"),"银行卡开户市");
            t_field($table->string("bank_type"),"银行卡类型");
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
