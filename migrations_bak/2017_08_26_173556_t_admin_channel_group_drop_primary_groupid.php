<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminChannelGroupDropPrimaryGroupid extends Migration
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
            t_field($table->integer("teacher_ref_type"),"二级招师渠道 ");
            t_field($table->integer("channel_id"),"一级招师渠道");
            // $table->primary(["teacher_ref_type","channel_id"]);
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
