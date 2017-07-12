<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTagsInfoTNewsInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_tool.t_news_headlines', function( Blueprint $table)
        {
            $table->string("tags_info");
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
        Schema::table('db_tool.t_news_headlines', function( Blueprint $table)
        {
            $table->dropColumn("tags_info");
        });
    }
}
