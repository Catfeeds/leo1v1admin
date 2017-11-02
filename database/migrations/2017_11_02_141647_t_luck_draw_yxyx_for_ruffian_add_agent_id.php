<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLuckDrawYxyxForRuffianAddAgentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_luck_draw_yxyx_for_ruffian', function( Blueprint $table)
        {
            t_field($table->integer("agent_id"),"agent中id");
            $table->index('agent_id');
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
