<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTLessonInfoAddCanSetFromLessonid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            $table->integer("can_set_as_from_lessonid");
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
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            $table->dropColumn("can_set_as_from_lessonid");
        });
        //
    }
}
