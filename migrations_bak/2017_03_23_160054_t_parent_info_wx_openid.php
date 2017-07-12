<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TParentInfoWxOpenid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('db_weiyi.t_parent_info', function( Blueprint $table)
        {
            t_field($table->string("wx_openid")->nullable() ,"微信 openid");
            $table->unique("wx_openid");
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
