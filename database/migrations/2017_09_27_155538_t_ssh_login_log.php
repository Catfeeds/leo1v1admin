<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSshLoginLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi_admin.t_ssh_login_log', function (Blueprint $table) {
            t_field($table->increments("id"),"编号id");
            t_field($table->integer("type"),"登录状态");
            t_field($table->string("serverip"),"主机ip");
            t_field($table->string("account"),"用户");
            t_field($table->string("loginip"),"登录ip");
            t_field($table->unsignedInteger("login_time"),"时间");
            $table->index([ "login_time" ]);
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
