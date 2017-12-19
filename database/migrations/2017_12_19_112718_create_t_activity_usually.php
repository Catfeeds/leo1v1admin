<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTActivityUsually extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_activity_usually', function(Blueprint $table) {
            t_field($table->increments("id"), "市场日常活动表");
            t_field($table->integer("gift_type"), "礼品类型");
            t_field($table->string("title",524), "活动标题");
            t_field($table->string("describe",2048), "活动描述");
            t_field($table->string("url",512), "活动链接");
            t_field($table->tinyInteger("activity_status"), "活动状态");
            t_field($table->integer("add_time"), "添加时间");
            t_field($table->string("pic_url"), "活动图片");
            t_field($table->integer("userid"), "添加人");

            $table->index('userid');
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
