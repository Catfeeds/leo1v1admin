<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentScoreInfoAddPaperStu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_student_score_info', function( Blueprint $table)
        {
            t_field($table->integer("paper_url"),"试卷地址");
            t_field($table->integer("paper_upload_time"),"试卷上传地址");           
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
