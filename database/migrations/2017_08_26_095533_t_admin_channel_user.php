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
        Schema::create('db_weiyi_admin.t_admin_channel_user', function( Blueprint $table)
        {
            t_field($table->integer("teacher_ref_type_id",true),"分渠道id");
            t_field($table->integer("channel_id"),"主渠道ID");
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
