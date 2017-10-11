<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRevisitAssessInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_revisit_assess_info', function( Blueprint $table)
        {
            $table->increments("id","id");
            t_field($table->integer("uid"),"助教uid");
            t_field($table->integer("stu_num"),"助教当月１号的在读学生个数");
            t_field($table->integer("revisit_num"),"助教当月已回访个数");
            t_field($table->integer("call_count"),"助教当月回访通话总时长");
            t_field($table->integer("create_time"),'');
            $table->index('uid');
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
