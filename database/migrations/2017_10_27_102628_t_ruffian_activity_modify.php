<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRuffianActivityModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_ruffian_activity', function( Blueprint $table)
        {
            $table->dropColumn('create_time');
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("prize_time"),"抽奖时间");
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
