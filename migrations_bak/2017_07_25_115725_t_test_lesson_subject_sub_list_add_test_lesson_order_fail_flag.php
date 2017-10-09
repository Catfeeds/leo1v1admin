<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectSubListAddTestLessonOrderFailFlag extends Migration
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
            t_field($table->integer("ass_test_lesson_order_fail_flag"),"助教签单失败分类");
            t_field($table->string("ass_test_lesson_order_fail_desc",1024),"签单失败说明");
            t_field($table->integer("ass_test_lesson_order_fail_set_time"),"签单失败设置时间");
            t_field($table->integer("ass_test_lesson_order_fail_set_adminid"),"签单失败设置人");
            $table->index("ass_test_lesson_order_fail_flag","ass_test_lesson_order_fail_flag");
            $table->index("ass_test_lesson_order_fail_set_time","ass_test_lesson_order_fail_set_time");
            $table->index("ass_test_lesson_order_fail_set_adminid","ass_test_lesson_order_fail_set_adminid");
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
