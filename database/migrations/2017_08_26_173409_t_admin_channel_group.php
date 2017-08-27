<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminChannelGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	Schema::dropIfExists('db_weiyi_admin.t_admin_channel_group');
        Schema::create('db_weiyi_admin.t_admin_channel_group', function( Blueprint $table)
        {
            t_field($table->integer("id",true),"id");
            t_field($table->integer("group_id"),"分渠道id");
            t_field($table->string("group_name"),"分渠道name");
            t_field($table->integer("channel_id"),"主渠道id");
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
