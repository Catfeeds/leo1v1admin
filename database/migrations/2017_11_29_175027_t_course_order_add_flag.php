<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCourseOrderAddFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_course_order', function( Blueprint $table)
        {
            $table->dropColumn("reset_lesson_count_flag");
            // t_field($table->tinyInteger("reset_lesson_count_flag"),"常规课上奥数课标识");
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
