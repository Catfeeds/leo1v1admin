<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectRequireAddAcceptStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("db_weiyi.t_test_lesson_subject_require", function(Blueprint $table) {
            t_field($table->tinyInteger('accept_status'),"老师试听课处理 0:未处理 1:已接受 2:已拒绝");
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
