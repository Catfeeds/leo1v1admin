<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFlowConfigEx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi_admin.t_flow_config', function (Blueprint $table) {
            t_comment($table, "审批流程图配置" );
            t_field($table->integer('flow_type'), "审批流程类型");
            t_field($table->text("json_data"), "审批流程json数据");
            $table->primary("flow_type");
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
