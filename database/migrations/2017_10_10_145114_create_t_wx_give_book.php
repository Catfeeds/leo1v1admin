<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTWxGiveBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_wx_give_book', function( Blueprint $table)
        {
            $table->increments("id","id");
            t_field($table->integer("create_time"),"分享时间");
            t_field($table->integer("parentid"),"家长id");
            t_field($table->integer("share_num"),"家长分享朋友圈次数");
            $table->index('parentid');
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
