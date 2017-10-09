<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonTimeModifyAddJiaowuDealTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_lesson_time_modify', function( Blueprint $table)
        {
            t_field($table->integer("deal_jiaowu"),"处理教务的adminid");
            t_field($table->string("deal_jiaowu_time",50),"教务处理调课的时间");
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
