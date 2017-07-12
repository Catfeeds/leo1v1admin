<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerInfoForTestLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_seller_student_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field($table->string("stu_score_info"),"成绩情况 " ); 
            \App\Helper\Utils::comment_field($table->string("stu_character_info"),"性格特点 " ); 
            \App\Helper\Utils::comment_field($table->string("stu_request_test_lesson_time_info"),"试听时间段 " ); 
            \App\Helper\Utils::comment_field($table->string("stu_request_lesson_time_info"),"正式上课后时间段  " ); 
            \App\Helper\Utils::comment_field($table->integer("stu_test_lesson_level"),"试听内容：初级，中级，高级   " ); 
            \App\Helper\Utils::comment_field($table->integer("stu_test_ipad_flag"),"销售是否已经连线测试 " ); 

            \App\Helper\Utils::comment_field($table->string("stu_lesson_content"),"本节课内容       " ); 
            \App\Helper\Utils::comment_field($table->string("stu_lesson_status"),"学生课堂状态     " ); 
            \App\Helper\Utils::comment_field($table->string("stu_study_status"),"学生吸收情况     " ); 
            \App\Helper\Utils::comment_field($table->string("stu_advantages"),"学生优点（不要过分）" ); 
            \App\Helper\Utils::comment_field($table->string("stu_disadvantages"),"学生缺点    " ); 
            \App\Helper\Utils::comment_field($table->string("stu_lesson_plan"),"培训计划（简述）" ); 
            \App\Helper\Utils::comment_field($table->string("stu_teaching_direction"),"教学方向    " ); 
            \App\Helper\Utils::comment_field($table->string("stu_textbook_info"),"教材及内容  " ); 
            \App\Helper\Utils::comment_field($table->string("stu_teaching_aim"),"教学目标    " ); 
            \App\Helper\Utils::comment_field($table->integer("stu_lesson_count"),"大致推荐课时数   " ); 
            \App\Helper\Utils::comment_field($table->string("stu_advice"),"意见、建议等（不少于50字）" ); 
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
