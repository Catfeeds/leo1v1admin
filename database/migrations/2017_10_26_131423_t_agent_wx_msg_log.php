<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentWxMsgLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_agent_wx_msg_log', function (Blueprint $table)
        {
            $table->increments('id',"");
            t_field($table->unsignedInteger("from_agentid"),"来自那个学员 ");
            t_field($table->unsignedInteger("to_agentid"),"发给那个学员");
            t_field($table->unsignedInteger("log_time"),"发送时间");
            t_field($table->unsignedInteger("agent_wx_msg_type"),"类型");
            t_field($table->string("msg"),"内容");
            t_field($table->unsignedInteger("succ_flag"),"是否发送成功");
            $table->index([ "to_agentid" ]);
            $table->index([ "log_time" ]);
            $table->index([ "from_agentid", "to_agentid", "agent_wx_msg_type" ], "one_row");
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
