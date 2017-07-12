<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
    }*/


class TCourseOrderAssFromTestLessonId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_course_order', function( Blueprint $table)
        {
            add_field($table->integer("ass_from_test_lesson_id"),"助教-来自哪节试听课");
            $table->index("ass_from_test_lesson_id");
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
