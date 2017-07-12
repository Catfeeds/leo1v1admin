<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema ;

class CreateTNewsAdInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_tool.t_news_ad_info', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('ad_url','500');
            $table->string('img_url','500');
            $table->string('url','500');
            $table->string('title','100');
            $table->string('intro','500');
            $table->integer('start_time');
            $table->integer('end_time');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('db_tool.t_news_ad_info');
    }
}
