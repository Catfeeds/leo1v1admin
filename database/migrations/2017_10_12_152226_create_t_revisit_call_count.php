<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRevisitCallCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_revisit_call_count', function( Blueprint $table)
        {
            $table->increments("count_id","count_id");
            t_field($table->integer("uid"),"助教uid");
            t_field($table->integer("userid"),"学生userid");
            t_field($table->integer("revisit_time1"),"学情回访时间");
            t_field($table->integer("revisit_time2"),"当天学情回访前对应的其他回访时间");
            t_field($table->integer("call_phone_id"),"呼叫电话id");
            t_field($table->integer("create_time"),'');
            $table->index('uid');
            $table->index('call_phone_id');
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
