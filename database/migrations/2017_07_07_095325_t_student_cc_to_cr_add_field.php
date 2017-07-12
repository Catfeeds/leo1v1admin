<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentCcToCrAddField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_student_cc_to_cr', function( Blueprint $table)
        {
            t_field($table->string("common_lesson_time",255),"常规课时间");
            t_field($table->integer("first_lesson_time"),"首次上课时间");

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
