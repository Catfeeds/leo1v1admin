<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectAddRecentResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_student_new', function( Blueprint $table)
        {
            t_field($table->string("class_rank",32),"班级排名");
            t_field($table->string("grade_rank",32),"年级排名");
            t_field($table->tinyInteger("academic_goal"),"升学目标");
            t_field($table->tinyInteger("test_stress"),"应试压力");
            t_field($table->tinyInteger("entrance_school_type"),"升学学校要求");
            t_field($table->tinyInteger("interest_cultivation"),"趣味培养");
            t_field($table->tinyInteger("extra_improvement"),"课外提高");
            t_field($table->tinyInteger("habit_remodel"),"习惯重塑");
            t_field($table->string("study_habit"),"学习习惯");
            t_field($table->string("interests_and_hobbies"),"兴趣爱好");
            t_field($table->string("character_type"),"学习习惯");
            t_field($table->string("need_teacher_style"),"所需老师风格");
            
                      
        });

        Schema::table('db_weiyi.t_test_lesson_subject', function( Blueprint $table)
        {
            t_field($table->integer("demand_urgency"),"需求急迫性");
            t_field($table->integer("quotation_reaction"),"报价反应");
            t_field($table->string("knowledge_point_location"),"知识点定位");
            t_field($table->string("recent_results"),"近期成绩");
            t_field($table->tinyInteger("advice_flag"),"是否进步");

            
                      
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
