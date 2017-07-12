<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TGoodVideoSendListAddTeaNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        Schema::table('db_weiyi.t_good_video_send_list', function( Blueprint $table)
        {
            t_field($table->integer("tea_num"),"推送的老师个数");
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
