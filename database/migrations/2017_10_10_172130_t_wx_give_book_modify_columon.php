<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TWxGiveBookModifyColumon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_wx_give_book', function( Blueprint $table)
        {
            $table->dropColumn('parentid');
            t_field($table->string("openid"),"用户openid");

            $table->index('openid');
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
