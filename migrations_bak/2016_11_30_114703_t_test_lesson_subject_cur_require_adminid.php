<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TTestLessonSubjectCurRequireAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_test_lesson_subject_require', function( Blueprint $table)
        {        
            add_field($table->integer("cur_require_adminid"),"当前申请人" );         
            $table->index("cur_require_adminid");
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
