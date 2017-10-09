<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::drop("db_weiyi.t_agent");
        Schema::create('db_weiyi.t_agent', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("parentid"),"上级转介绍id");
            t_field($table->integer("userid")->nullable(),"用户id");
            t_field($table->string("phone",16),"手机号");
            t_field($table->string("wx_openid",128) ->nullable() ,"微信id");
            t_field($table->integer("create_time"),"创建时间");
            $table->unique("userid");
            $table->unique("wx_openid");
            $table->unique("phone");
            $table->index("parentid");
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
        Schema::drop("db_weiyi.t_agent");
        //
    }
}
