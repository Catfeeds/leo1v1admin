<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUserLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_user_log', function(Blueprint $table) {
            t_field($table->increments("id"),"用户日志表");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("userid"),"用户ID");
            t_field($table->integer("adminid"),"管理员ID");
            t_field($table->integer("user_log_type"),"用户日志类型");
            t_field($table->string("msg", 1024),"消息");
            $table->index(['add_time']);
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
