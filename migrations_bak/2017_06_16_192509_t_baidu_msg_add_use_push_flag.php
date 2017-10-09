<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TBaiduMsgAddUsePushFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_baidu_msg', function( Blueprint $table)
        {
            t_field($table->tinyInteger("use_push_flag"),"是否使用公共消息模板 0 不是 1 是");
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
