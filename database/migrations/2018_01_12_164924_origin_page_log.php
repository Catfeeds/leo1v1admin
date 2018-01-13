<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OriginPageLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create("db_weiyi_admin.t_log_event_type", function( Blueprint $table)
        {
            //表注释
            t_comment($table, "日志事件分类" );
            //字段以及注释
            t_field($table->integer("event_type_id",true) ,"自增id");
            t_field($table->string("project") ,"项目:origin, ...");
            t_field($table->string("sub_project") ,"子项目:今日头条0501, ...");
            t_field($table->string("event_name") ,"事件名");
            t_field($table->integer("add_time") ,"加入时间");
            //唯一索引
            $table->unique(["project", "sub_project" ,"event_name"], "event_name" );
        });

        Schema::create("db_weiyi_admin.t_log_event_log", function( Blueprint $table)
        {
            //表注释
            t_comment($table, "日志事件记录" );
            //字段以及注释
            t_field($table->integer("id",true) ,"自增id");
            t_field($table->unsignedInteger("logtime") ,"记录时间");
            t_field($table->integer("event_type_id") ,"事件类型id");
            t_field($table->integer("value") ,"value");
            t_field($table->unsignedInteger("ip") ,"ip");
            //唯一索引
            $table->index("logtime");
            $table->index("event_type_id");
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
