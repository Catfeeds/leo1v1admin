<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewsInfoPushStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_tool.t_news_info', function (Blueprint $table)
        {
            $table->integer("push_status");
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
        Schema::table('db_tool.t_news_info', function (Blueprint $table)
        {
            $table->integer("push_status");
        });
    }
}
