<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSshLoginLogNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("db_weiyi_admin.t_ssh_login_log");

        Schema::create('db_weiyi_admin.t_ssh_login_log', function (Blueprint $table) {
            t_field($table->increments("id"),"编号id");
            t_field($table->unsignedInteger("login_time"),"时间");
            t_field($table->unsignedInteger("server_ip"),"主机ip");
            t_field($table->unsignedInteger("login_ip"),"登录ip");
            t_field($table->string("account",32),"用户");
            t_field($table->integer("login_succ_flag"),"是否登录成功");
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
