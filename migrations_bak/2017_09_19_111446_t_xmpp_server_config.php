<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TXmppServerConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi.t_xmpp_server_config', function( Blueprint $table)
        {
            $table->increments("id","id");
            t_field($table->string("server_name"),"服务器名称: q_27 ..");
            t_field($table->string("server_desc"),"说明 ");
            t_field($table->string("ip",20),"ip");
            t_field($table->integer("xmpp_port"),"xmpp_port");
            t_field($table->integer("webrtc_port"),"dobango webrtc_port");
            t_field($table->integer("websocket_port"), "网页上直接看课程");
            t_field($table->integer("weights"), "权值");
            $table->unique("server_name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop( 'db_weiyi.t_xmpp_server_config' );
        //
    }
}
