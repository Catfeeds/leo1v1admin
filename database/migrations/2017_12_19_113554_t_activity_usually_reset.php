<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TActivityUsuallyReset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi.t_activity_usually');
        Schema::create('db_weiyi.t_activity_usually', function(Blueprint $table) {
            t_field($table->increments("id"), "市场日常活动表");
            t_field($table->integer("gift_type"), "礼品类型");
            t_field($table->string("title",524), "活动标题");
            t_field($table->string("describe",2048), "活动描述");
            t_field($table->string("url",512), "活动链接");
            t_field($table->tinyInteger("activity_status"), "活动状态");
            t_field($table->integer("add_time"), "添加时间");
            t_field($table->integer("uid"), "添加人");

            $table->index('uid');
            $table->index('add_time');
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
