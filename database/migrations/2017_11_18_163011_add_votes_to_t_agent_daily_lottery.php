<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToTAgentDailyLottery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_agent_daily_lottery', function (Blueprint $table) {
            //
            t_field($table->integer('is_can_cash_flag')->default(0),"是否可提现标识");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_agent_daily_lottery', function (Blueprint $table) {
            //
        });
    }
}
