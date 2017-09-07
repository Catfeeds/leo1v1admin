<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentAddStarAllMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('db_weiyi.t_agent', function( Blueprint $table)
        {
            t_field($table->integer("star_count"),"星星个数");
            t_field($table->integer("all_yxyx_money"),"总收入");
            t_field($table->integer("all_open_cush_money"),"可提现金额");
            t_field($table->integer("all_have_cush_money"),"已提现金额");
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
