<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class TTestLessonSubjectRequireAddSellerRequireChangeLessonTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_test_lesson_subject_require', function( Blueprint $table)
        {        
            add_field($table->integer("require_change_lesson_time"),"更改课程目标时间" );         
            $table->index("require_change_lesson_time");
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
