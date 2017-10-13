<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssWeeklyInfoAddNewStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_ass_weekly_info', function( Blueprint $table)
        {
            t_field($table->integer("new_student"),"新签人数");
            t_field($table->integer("new_lesson_count"),"购买课时");
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
