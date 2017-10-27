<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTLuckDrawYxyxForRuffian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_luck_draw_yxyx_for_ruffian', function( Blueprint $table)
        {
            $table->increments('id');
            t_field($table->integer("luck_draw_time"),"抽奖时间");
            t_field($table->integer("luck_draw_adminid"),"抽奖人");
            t_field($table->integer("money"),"中奖金额");
            t_field($table->integer("deposit_time"),"存入时间");
            t_field($table->tinyInteger("is_deposit"),"是否存入总金额中 0:未存入 1:已存入");

            $table->index('luck_draw_adminid');
            $table->index('luck_draw_time');
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
