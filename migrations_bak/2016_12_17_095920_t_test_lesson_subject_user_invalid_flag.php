<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;



class TTestLessonSubjectUserInvalidFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //       
        Schema::table('db_weiyi.t_test_lesson_subject', function( Blueprint $table)
        {
            t_field($table->integer("seller_student_sub_status"),"子状态" );
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
