<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherRecordListAddRecordMonitorClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_record_list', function( Blueprint $table)
        {
            t_field($table->string("record_monitor_class",5000),"监课情况");
            t_field($table->string("record_rank"),"评分等级");
            t_field($table->string("record_lesson_list"),"试听课id list");

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
