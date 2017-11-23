<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToTAgentIncomeLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_agent_income_log', function (Blueprint $table) {
            //
            t_field($table->integer('agent_money_ex_id'),"优学优享活动id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_agent_income_log', function (Blueprint $table) {
            //
        });
    }
}
