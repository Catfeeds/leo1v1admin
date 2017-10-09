<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectRequireAddGrabFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_test_lesson_subject_sub_list', function( Blueprint $table)
        {
            t_field($table->tinyInteger("grab_flag"),"是否为老师抢课所排课程 0 不是 1 是");
            t_field($table->integer("grab_time"),"抢课时间");
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
        Schema::table('db_weiyi.t_test_lesson_subject_sub_list', function( Blueprint $table)
        {
            $table->dropColumn("grab_flag");
            $table->dropColumn("grab_time");
        });
    }
}
