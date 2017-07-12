<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectRequireAddGreenChannelTeacherid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_test_lesson_subject_require', function( Blueprint $table)
        {
            t_field($table->integer("green_channel_teacherid"),"绿色通道之销售确认的教师id" );
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
