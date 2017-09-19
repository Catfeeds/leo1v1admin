<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddXmppServer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('db_weiyi.t_xmpp_server_config', function( Blueprint $table)
        {
            $table->unique("ip");
        });


        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field( $table->string("xmpp_server_name"), "xmpp 服务器 from t_xmpp_server_config ");
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
