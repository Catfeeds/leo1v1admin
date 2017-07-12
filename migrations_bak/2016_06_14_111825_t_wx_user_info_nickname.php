<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TWxUserInfoNickname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	   Schema::table('db_weiyi_admin.t_wx_user_info', function (Blueprint $table)
       {
           $table->string("nickname");
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
	   Schema::table('db_weiyi_admin.t_wx_user_info', function (Blueprint $table)
       {
           $table->dropColumn("nickname");
        });

        //
    }
}
