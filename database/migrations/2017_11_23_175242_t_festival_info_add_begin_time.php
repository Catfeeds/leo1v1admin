<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFestivalInfoAddBeginTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_festival_info', function (Blueprint $table) {
            //
            t_field($table->string('name',64),"假期名称");
            t_field($table->integer('begin_time'),"开始时间");
            t_field($table->integer('end_time'),"结束时间");
            t_field($table->tinyInteger('days'),"假期时长");
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
