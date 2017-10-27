<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAgentGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_agent_group', function (Blueprint $table) {
            t_field($table->increments("group_id"),"yxyx团id");
            t_field($table->string("group_name"),'团名称');
            t_field($table->integer("create_time"),'创建时间');
            t_field($table->integer("colconel_agent_id"),'团长id');
            $table->index('colconel_agent_id');
            $table->index('create_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_agent_group');
    }
}
