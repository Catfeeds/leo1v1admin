<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRuffianShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_ruffian_share', function( Blueprint $table)
        {
            $table->increments('id');

            t_field($table->tinyInteger("is_share_flag"),"0:未分享 1:已分享 是否分享");
            t_field($table->integer("share_time"),"分享时间");
            t_field($table->integer("parentid"),"分享人id");
            // t_field($table->integer("agent_money"),"平台合作代理费(平台合作的抽成，非老师工资)");
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
