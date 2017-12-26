<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TActivityUsuallyAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_activity_usually', function( Blueprint $table)
        {
            t_field($table->string("shareImgUrl"), "分享页面链接");
            t_field($table->string("coverImgUrl"), "封面页面链接");
            t_field($table->string("activityImgUrl"), "活动页面链接");
            t_field($table->string("followImgUrl"), "关注页面链接");

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
