<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAlreadyLessonCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("already_lesson_count"),
                "当前累计课时" );
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
            $table->dropColumn("already_lesson_count");
        });


        //
    }
}
