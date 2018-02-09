<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddProtocolResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function(Blueprint $table) {
            t_field($table->tinyInteger("protocol_results"),"兼职老师签订协议结果");
            t_field($table->integer("protocol_time"),"兼职老师签订协议时间");
        });
        Schema::table('db_weiyi.t_teacher_record_list', function(Blueprint $table) {
            t_field($table->tinyInteger("protocol_results_record"),"兼职老师签订协议结果");
            t_field($table->integer("protocol_time_record"),"兼职老师签订协议时间");
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
