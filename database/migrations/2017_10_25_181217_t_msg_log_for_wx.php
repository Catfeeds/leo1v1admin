<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMsgLogForWx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // Schema::create('db_weiyi.t_msg_log_for_wx', function (Blueprint $table) {
        //         $table->increments('id');
        //         t_field($table->integer("click_time"),"点击链接时间");
        //         t_field($table->integer("click_account_id"),"点击人");
        //         t_field($table->integer("admin_type"),"点击人类别 1:家长 2:老师");
        //         t_field($table->integer("msg_type"),"消息类别");
        //         $table->index('click_account_id');
        //         $table->index('click_time');
        // });

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
