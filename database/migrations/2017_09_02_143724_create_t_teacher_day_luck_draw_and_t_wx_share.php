<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherDayLuckDrawAndTWxShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_teacher_day_luck_draw', function( Blueprint $table)
        {
            $table->increments("id");

           t_field($table->integer("do_num"),"抽奖次数");
           t_field($table->integer("do_time"),"抽奖时间");
           t_field($table->integer("teacherid"),"老师id");
           t_field($table->integer("money"),"中奖金额");
           t_field($table->integer("is_grant_flag"),"奖品是否发放 0:未发放 1:已发放");
            // t_field($table->integer("id_do_flag"),"是否处理 0:未处理 1:已处理");
            // t_field($table->integer("create_time"),"添加时间");
            // t_field($table->string("pdf_url"),'pdf课件链接');

            // $table->index(["lessonid"]);
            // $table->index(["create_time"]);
            // $table->index(["deal_time"]);
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
