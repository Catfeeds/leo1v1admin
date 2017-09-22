<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTGrabLessonLinkVisitInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_grab_lesson_link_visit_info', function( Blueprint $table)
        {
            $table->increments("id");
            t_field($table->integer("grabid"),"抢课链接id");
            t_field($table->integer("visitid"),"访问者id");
            t_field($table->integer("create_time"),"访问时间");
            t_field($table->tinyInteger("operation"),"操作信息 0:未抢课;1:抢课，不成功; 2:抢课，成功");

            $table->index("grabid");
            $table->index("visitid");
            $table->index("create_time");
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
