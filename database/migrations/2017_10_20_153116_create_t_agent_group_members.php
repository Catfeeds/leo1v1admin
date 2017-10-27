<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAgentGroupMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_agent_group_members', function (Blueprint $table) {
            t_field($table->increments("id"),"ID");
            t_field($table->integer("group_id"),'团id');
            t_field($table->integer("agent_id"),'团员id');
            t_field($table->integer("add_time"),'添加时间');
            $table->unique(['group_id', 'agent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_agent_group_members');
    }
}
