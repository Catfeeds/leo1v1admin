<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi.t_agent', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("parentid")->nullable(),"上级转介绍id");
            t_field($table->integer("userid")->nullable(),"用户id");
            t_field($table->string("phone",16),"手机号");
            t_field($table->string("wx_openid",128),"微信id");
            t_field($table->integer("create_time"),"创建时间");
            $table->unique("userid");
            $table->unique("wx_openid");
            $table->index("parentid");
        });

        Schema::create('db_weiyi.t_agent_order', function (Blueprint $table){
            t_field($table->integer("orderid"),"");
            t_field($table->integer("pid"),"上级转介绍id");
            t_field($table->integer("p_price"),"上级转介绍费");
            t_field($table->integer("ppid"),"上上级转介绍id");
            t_field($table->integer("pp_price"),"上上级转介绍费");
            t_field($table->integer("create_time"),"创建时间");
            $table->primary("orderid");
            $table->index("pid");
            $table->index("ppid");
        });
        // //
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
