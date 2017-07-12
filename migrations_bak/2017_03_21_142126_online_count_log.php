<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OnlineCountLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi_admin.t_online_count_log', function (Blueprint $table){
            t_field($table->integer("logtime"),"时间");
            t_field($table->integer("online_count"), "在线人数" );
            $table->primary("logtime");
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
