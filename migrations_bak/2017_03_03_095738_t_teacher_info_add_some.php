<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddSome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->string("idcard",50),"老师身份证号");
            t_field($table->string("bankcard",50),"老师银行证号");
            t_field($table->string("bank_address",500),"开户行及支行");
            t_field($table->string("bank_account",100),"持卡人姓名");
            t_field($table->tinyInteger("wx_use_flag")->default(1),"微信工资页面能否看到 0 不能 1 能");
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
