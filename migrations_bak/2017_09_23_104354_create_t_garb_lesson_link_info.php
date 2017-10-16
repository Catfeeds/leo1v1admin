<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTGarbLessonLinkInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // Schema::create('db_weiyi.t_grab_lesson_link_info', function( Blueprint $table)
        // {
        //     $table->increments("grabid");
        //     t_field($table->string("grab_lesson_link"),"抢课链接");
        //     t_field($table->string("requireids"),"课程ｉｄ集，英文逗号分割组合");
        //     t_field($table->integer("live_time"),"链接有效时间");
        //     t_field($table->integer("create_time"),"生成时间");
        //     t_field($table->integer("adminid"),"执行者id");

        //     $table->index("create_time");
        //     $table->index("adminid");
        // });
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
