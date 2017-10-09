<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTParentLuckDrawInWx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_parent_luck_draw_in_wx', function (Blueprint $table){
            t_field($table->increments("id"),"");

            t_field($table->string("prize_code"),"奖品券");
            t_field($table->integer("userid"),"家长id");
            t_field($table->integer("use_flag"),"使用状态 0:未使用 1:已使用");
            t_field($table->integer("price_type"),"奖品类型");
            t_field($table->integer("price"),"金额");
            t_field($table->string("receive_time",100),"奖品领取时间");
            t_field($table->string("use_time",100),"奖品使用时间");

            $table->index('userid');
            $table->index('use_time');
            $table->index('receive_time');
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
