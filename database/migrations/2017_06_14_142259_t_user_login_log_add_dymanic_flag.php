<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUserLoginLogAddDymanicFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_user_login_log', function( Blueprint $table)
        {
            t_field($table->tinyInteger("dymanic_flag"),"是否是临时密码登陆 0 不是 1 是");
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
