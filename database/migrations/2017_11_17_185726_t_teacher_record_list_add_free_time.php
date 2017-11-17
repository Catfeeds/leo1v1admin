<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherRecordListAddFreeTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_record_list', function( Blueprint $table)
        {
            t_field($table->tinyInteger("gender"),"性别");
            t_field($table->tinyInteger("work_year"),"教龄");
            t_field($table->string("teacher_textbook"),"教材版本");
            t_field($table->string("region",64),"地区");
            t_field($table->text("free_time"),"空闲时间");
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
