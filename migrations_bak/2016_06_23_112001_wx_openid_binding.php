<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WxOpenidBinding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            $table->dropColumn("wx_openid");
        });

	   Schema::create('t_wx_openid_bind', function (Blueprint $table)
       {
           $table->string("openid");
           $table->integer("role");
           $table->integer("userid");
           $table->primary(["openid","role"]);
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
