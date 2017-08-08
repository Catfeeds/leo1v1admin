<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTYxyxWxnewsInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_yxyx_wxnews_info', function (Blueprint $table){
            t_field($table->increments("id"),"");

            t_field($table->string("title"),"标题");
            t_field($table->string("type"),"新闻分类");
            t_field($table->string("des"),"内容描述");
            t_field($table->string("pic"),"图片地址");
            t_field($table->string("new_link"),"跳转地址");
            t_field($table->integer("adminid"),"添加者id");
            t_field($table->integer("create_time"),"添加时间");

            $table->index('adminid');
            $table->index('type');
            $table->index('create_time');
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
