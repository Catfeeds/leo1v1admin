<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTCcDayTop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_cc_day_top', function (Blueprint $table) {
            t_comment($table, "cc每日业绩前20表" );
            t_field($table->increments('id'), "ID");
            t_field($table->integer('uid'), "cc用户id");
            t_field($table->integer('score'), "cc业绩得分[×100]");
            t_field($table->tinyInteger('rank'), "cc每日排名");
            t_field($table->integer("add_time"), "排名的日期");
            $table->index('add_time', 'time_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_cc_day_top');
    }
}
