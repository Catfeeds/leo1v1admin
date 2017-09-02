<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTWxShareAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_wx_share', function( Blueprint $table)
        {
            $table->increments("id");

            t_field($table->integer("teacherid"),"老师id");
            t_field($table->integer("type")," 分享类型 1:微信朋友圈 2:其他");
            t_field($table->integer("share_time"),"添加时间");

            $table->index(["teacherid"]);
            $table->index(["share_time"]);


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
