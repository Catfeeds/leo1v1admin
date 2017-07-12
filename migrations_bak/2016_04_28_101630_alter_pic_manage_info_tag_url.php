<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPicManageInfoTagUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_pic_manage_info', function (Blueprint $table)
        {
            $table->dropColumn("tag_icon");
            $table->dropColumn("url");
            $table->dropColumn("click_status");
            $table->string('img_tags_url','500');
            $table->string('img_url','500');
            $table->integer('status')->default(1);
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
        Schema::table('t_pic_manage_info', function (Blueprint $table)
        {
            $table->string('tag_icon','500');
            $table->string('url','500');
            $table->integer('click_status')->default(1);
            $table->dropColumn("img_tags_url");
            $table->dropColumn("img_url");
            $table->dropColumn("status");
        });
    }
}
