<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTPicManageInfo extends Migration
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
            $table->dropColumn("jump_url");
            $table->dropColumn("title_share");
            $table->dropColumn("info_share");

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
