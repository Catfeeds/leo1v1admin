<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectRequireFailOrderInfo extends Migration
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
            t_field($table->integer("test_lesson_order_fail_flag"),"签单失败分类" );
            t_field($table->string( "test_lesson_order_fail_desc",1024),"签单失败说明" );
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
