<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTestLogin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi_admin.t_test_login', function (Blueprint $table) {
            t_field($table->increments("id"),"编号id");
            t_field($table->string("login_user"),"用户");
            t_field($table->string("ip"),"主机ip");
            t_field($table->integer("type"),"登录状态");
            t_field($table->timestamp("login_time"),"时间");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_test_login', function (Blueprint $table) {
            //
        });
    }
}
