<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TBaiduMsg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_baidu_msg', function (Blueprint $table)
        {
            $table->increments('messageid');
            $table->string('content','500');
            $table->string('date','50');
            $table->string('value','1000');
            $table->integer('push_num');
            $table->integer('message_type');
            $table->integer('userid');
            $table->integer('device_type');
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_baidu_msg');
    }
}
