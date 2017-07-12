<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectRequireSetFailTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_test_lesson_subject_require', function( Blueprint $table)
        {
            t_field($table->integer("test_lesson_order_fail_set_time"),"签单失败设置时间" );
            $table->index("test_lesson_order_fail_set_time","fail_set_time");
        });
        Schema::table('db_weiyi.t_order_info', function( Blueprint $table)
        {
            t_field($table->integer("get_packge_time"),"获取大礼包时间" );
            $table->index("get_packge_time");
        });

        //
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
