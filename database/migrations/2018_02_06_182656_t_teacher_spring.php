<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSpring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_spring', function(Blueprint $table) {
            t_comment($table, "老师春节红包活动表");
            t_field($table->increments("id"), "");
            t_field($table->integer("add_time"), "抽奖时间");
            t_field($table->integer("teacherid"), "老师id");
            t_field($table->integer("rank"), "当天排名");
            t_field($table->integer("result"), "中奖结果");
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
