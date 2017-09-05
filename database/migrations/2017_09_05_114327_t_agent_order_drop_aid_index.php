<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentOrderDropAidIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_agent_order', function ($table) {
            $table->dropUnique(['aid']); // Drops index 'geo_state_index'
        });
        Schema::table('db_weiyi.t_agent_order', function ($table) {
            $table->index(['aid']); // Drops index 'geo_state_index'
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
