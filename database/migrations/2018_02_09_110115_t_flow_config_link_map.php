<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFlowConfigLinkMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('db_weiyi_admin.t_flow_config', function(Blueprint $table) {
            t_field($table->text("node_map"), "节点信息, start节点 id=0 , end节点:id=1 ");
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
