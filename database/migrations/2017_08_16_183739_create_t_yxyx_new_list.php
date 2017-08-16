<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTYxyxNewList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_yxyx_new_list', function( Blueprint $table)
        {
            $table->increments("id");
            t_field($table->string("new_title"),"新闻标题");
            t_field($table->longText("new_content"),"新闻内容");
            t_field($table->string("new_pic"),"新闻标题");
            t_field($table->integer("adminid"),"作者id");
            t_field($table->integer("create_time"),"添加时间");

            $table->index(["adminid"]);

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
