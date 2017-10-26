<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRuffianActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_ruffian_activity', function (Blueprint $table) {
                $table->increments('id');
                t_field($table->integer("parentid"),"家长id");
                t_field($table->integer("create_time"),"抽奖时间");
                t_field($table->tinyInteger("is_share_flag")," 是否分享 0:未分享 1:已分享");
                t_field($table->integer("prize_list"),"奖品种类");
                t_field($table->string("get_prize_time"),"领奖时间");
                t_field($table->integer("presenterid"),"发奖人");
                $table->index('parentid');
                $table->index('presenterid');
                $table->index('create_time');
                $table->index('get_prize_time');
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
