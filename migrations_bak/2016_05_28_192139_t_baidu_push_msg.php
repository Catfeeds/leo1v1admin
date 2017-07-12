<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TBaiduPushMsg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_baidu_push_msg', function (Blueprint $table)
        {
            $table->increments('messageid');
            $table->integer('message_type');
            $table->string('message_content','1000');
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
        Schema::drop('t_baidu_push_msg');
    }
}
