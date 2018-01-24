<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPersonalityPoster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_tool.t_personality_poster', function(Blueprint $table) {
            t_comment($table,"市场部个性海报-统计表");
            t_field($table->increments("id"), "");
            t_field($table->integer("uid"), "分享人id");
            t_field($table->integer("parentId"), "家长id");
            t_field($table->string("par_openid"), "家长openid");
            t_field($table->string("phone",100), "学生号码");
            t_field($table->integer("posterNum"), "制作海报次数");
            t_field($table->integer("clickNum"), "家长点击次数");
            t_field($table->integer("forwardNum"), "转发次数");
            t_field($table->string("media_id",100), "照片mediaId");
            t_field($table->string("bgImgUrl"), "背景图片链接");
            t_field($table->string("qr_code_url"), "二维码链接");
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
