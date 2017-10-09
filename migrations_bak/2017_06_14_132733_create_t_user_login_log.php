<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTUserLoginLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_user_login_log', function( Blueprint $table)
        {
            $table->increments("id");
            t_field($table->integer("userid"),"登陆用户id");
            t_field($table->integer("login_time"),"登陆时间");
            t_field($table->string("nick",50),"登陆用户id");
            t_field($table->string("ip",50),"登陆ip");
            t_field($table->tinyInteger("role"),"登陆角色");
            t_field($table->tinyInteger("login_type"),"登陆类型 0 app登陆");
            $table->unique(["userid","login_time","ip"],"unique_user");
            $table->index("userid","userid");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('db_weiyi.t_user_login_log');
    }
}
