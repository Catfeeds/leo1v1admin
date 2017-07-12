<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTPicManageInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_pic_manage_info', function( Blueprint $table)
        {
            $table->integer("jump_url");
            $table->integer("title_share");
            $table->integer("info_share");
            $table->integer("start_time");
            $table->integer("end_time");
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
        Schema::table('t_pic_manage_info', function( Blueprint $table)
        {
            $table->dropColumn("jump_url");
            $table->dropColumn("title_share");
            $table->dropColumn("info_share");
            $table->dropColumn("start_time");
            $table->dropColumn("end_time");
        });
    }
}
