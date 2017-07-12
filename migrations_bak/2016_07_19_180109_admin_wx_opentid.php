<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminWxOpentid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //nullable
        //
        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            $table->string("wx_openid")->nullable();
            $table->unique("wx_openid");
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
