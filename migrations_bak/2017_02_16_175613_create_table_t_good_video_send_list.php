<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTGoodVideoSendList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_good_video_send_list', function (Blueprint $table)
        {
            $table->integer("id",true);
            t_field($table->integer("send_time"),"推送时间");
            t_field($table->string("account"),"操作人");
            t_field($table->string("send_reason"),"推荐理由");
            t_field($table->string("teacher"),"推荐老师");            
            t_field($table->string("url"),"");
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
