<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TModifyStudentTestPaper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
       
        Schema::table('db_weiyi.t_student_test_paper', function(Blueprint $table) {         
            t_field($table->integer("paper_type"), "试卷类型");
            t_field($table->integer("paper_question_num"), "试卷题目数量");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
