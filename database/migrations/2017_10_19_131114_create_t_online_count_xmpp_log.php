<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTOnlineCountXmppLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("db_weiyi_admin.t_online_count_xmpp_log");

        Schema::create('db_weiyi_admin.t_online_count_xmpp_log', function (Blueprint $table) {
            t_field($table->integer("xmpp_id"),"xmpp_id");
            t_field($table->integer("logtime"),"时间");
            t_field($table->integer("online_count"),"在线合计");
            $table->primary(['xmpp_id','logtime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_online_count_xmpp_log');
    }
}
