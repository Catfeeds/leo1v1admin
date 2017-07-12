<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonLogListAddDelFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_test_lesson_log_list', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("del_flag"),
                "取消多余记录");
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
        Schema::table('t_test_lesson_log_list', function( Blueprint $table)
        {
            $table->dropColumn("del_flag");
        });

        //
    }
}
