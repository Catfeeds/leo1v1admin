<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminChannelUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_admin_channel_user', function( Blueprint $table)
        {
            t_field($table->integer("admin_id",true),"成员ID");
            t_field($table->integer("group_id"),"次渠道id");
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
