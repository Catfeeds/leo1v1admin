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
        Schema::dropIfExists('db_weiyi_admin.t_admin_channel_user');
        Schema::create('db_weiyi_admin.t_admin_channel_user', function( Blueprint $table)
        {
            t_field($table->integer("id",true),"");
            t_field($table->integer("admin_id"),"成员ID");
            t_field($table->integer("admin_name"),"成员name");
            t_field($table->string("admin_phone"),"成员phone");
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
