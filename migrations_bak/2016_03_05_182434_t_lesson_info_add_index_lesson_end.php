<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Schema ;

class TLessonInfoAddIndexLessonEnd extends Migration
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
            $table->index("lesson_end" );
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
            $table->dropIndex("t_lesson_info_lesson_end_index");
        });
    }
}
