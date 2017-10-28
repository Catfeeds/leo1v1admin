<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PageTrack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi_admin.t_web_page_info', function( Blueprint $table)
        {
            $table->increments('web_page_id');
            t_field($table->string("title") ,"标题");
            t_field($table->string("url") ,"地址");
            t_field($table->unsignedInteger("add_time") ,"添加时间");
            t_field($table->integer("add_adminid") ,"添加人");
            t_field($table->integer("del_flag"),"删除表示");
        });

        Schema::create('db_weiyi_admin.t_web_page_trace_log', function( Blueprint $table)
        {
            $table->increments('id');
            t_field($table->integer("web_page_id") ,"");
            t_field($table->integer("from_adminid") ,"来自谁的分享");
            t_field($table->unsignedInteger("ip") ,"访问ip" );
            t_field($table->unsignedInteger("log_time") ,"日志时间");
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
