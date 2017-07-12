<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherRecordListAddComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_record_list', function( Blueprint $table)
        {
            t_field($table->string("courseware_flag"),"试听课有无课件");
            t_field($table->integer("courseware_flag_score"),"试听课有无课件评分");
            t_field($table->string("lesson_preparation_content"),"备课内容与试听需求匹配度描述");
            t_field($table->integer("lesson_preparation_content_score"),"备课内容与试听需求匹配度评分");
            t_field($table->string("courseware_quality"),"课件质量描述");
            t_field($table->integer("courseware_quality_score"),"课件质量评分");
            t_field($table->string("tea_process_design"),"教学过程设计描述");
            t_field($table->integer("tea_process_design_score"),"教学过程设计评分");
            t_field($table->string("class_atm"),"课堂氛围描述");
            t_field($table->integer("class_atm_score"),"课堂氛围评分");
            t_field($table->string("tea_method"),"讲题方法思路描述");
            t_field($table->integer("tea_method_score"),"讲题方法思路评分");
            t_field($table->string("knw_point"),"知识点讲解描述");
            t_field($table->integer("knw_point_score"),"知识点讲解评分");
            t_field($table->string("dif_point"),"重难点把握描述");
            t_field($table->integer("dif_point_score"),"重难点把握评分");
            t_field($table->string("teacher_blackboard_writing"),"板书书写描述");
            t_field($table->integer("teacher_blackboard_writing_score"),"板书书写评分");
            t_field($table->string("tea_rhythm"),"课程节奏描述");
            t_field($table->integer("tea_rhythm_score"),"课程节奏评分");
            t_field($table->string("content_fam_degree"),"课本内容是否熟悉描述");
            t_field($table->integer("content_fam_degree_score"),"课本内容是否熟悉评分");
            t_field($table->string("answer_question_cre"),"题目解答描述");
            t_field($table->integer("answer_question_cre_score"),"题目解答评分");
            t_field($table->string("language_performance"),"语言表达组织能力描述");
            t_field($table->integer("language_performance_score"),"语言表达组织能力评分");
            t_field($table->string("tea_attitude"),"教学态度描述");
            t_field($table->integer("tea_attitude_score"),"教学态度评分");
            t_field($table->string("tea_concentration"),"教学专注度描述");
            t_field($table->integer("tea_concentration_score"),"教学专注度评分");
            t_field($table->string("tea_accident"),"教学事故描述");
            t_field($table->integer("tea_accident_score"),"教学事故评分");

            t_field($table->string("tea_operation"),"软件操作描述");
            t_field($table->integer("tea_operation_score"),"软件操作评分");
            t_field($table->string("tea_environment"),"周边环境描述");
            t_field($table->integer("tea_environment_score"),"周边环境评分");
            t_field($table->string("class_abnormality"),"课程异常情况处理描述");
            t_field($table->integer("class_abnormality_score"),"课程异常情况处理评分");

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
