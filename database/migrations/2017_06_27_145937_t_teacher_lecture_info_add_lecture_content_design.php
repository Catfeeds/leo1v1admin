<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherLectureInfoAddLectureContentDesign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_lecture_info', function( Blueprint $table)
        {
            t_field($table->integer("lecture_content_design_score"),"讲义设计得分");
            t_field($table->integer("lecture_combined_score"),"讲义结合得分");
            t_field($table->integer("course_review_score"),"讲义设计得分");
            t_field($table->string("retrial_info",100),"重审淘汰情况");
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
