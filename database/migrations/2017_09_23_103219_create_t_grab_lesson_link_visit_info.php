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
            $table->increments("visitid");
            t_field($table->integer("grabid"),"抢课链接id");
            t_field($table->integer("teacherid"),"访问老师id");
            t_field($table->integer("create_time"),"访问时间");
            t_field($table->tinyInteger("operation"),"操作信息 0:未抢课;1:抢课");

            $table->index("grabid");
            $table->index("teacherid");
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
