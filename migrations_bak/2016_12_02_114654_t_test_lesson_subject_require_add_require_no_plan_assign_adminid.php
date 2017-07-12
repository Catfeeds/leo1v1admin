<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class TTestLessonSubjectRequireAddRequireNoPlanAssignAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("t_test_lesson_subject_require",function(Blueprint $table){
            add_field($table->integer("require_assign_adminid"),"申请未排分配者");
            add_field($table->integer("require_assign_time"),"申请未排分配时间");
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
