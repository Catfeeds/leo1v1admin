<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTLocationSubjectGradeTextbookInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_student_subject_list', function( Blueprint $table)
        {
            t_field($table->string("province",64),"省");
            t_field($table->string("city",64),"地级市");

            t_field($table->integer("subject"),"科目");
            t_field($table->integer("grade"),"年级");
            t_field($table->string("teacher_textbook"),"教材版本");
            t_field($table->string("educational_system"),"学制");


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
