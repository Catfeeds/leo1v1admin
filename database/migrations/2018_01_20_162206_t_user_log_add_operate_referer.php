<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUserLogAddOperateReferer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_user_log', function( Blueprint $table)
        {
            t_field($table->string("operate_referer","1000"),"操作来源 PHP 中的 _SERVER referer");
            t_field($table->string("operate_request","1000"),"操作请求接口 PHP 中的 _SERVER REQUEST_URI ");
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
