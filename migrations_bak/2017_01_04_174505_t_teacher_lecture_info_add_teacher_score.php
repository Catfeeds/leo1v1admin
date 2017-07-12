<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherLectureInfoAddTeacherScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_lecture_info', function( Blueprint $table)
        {
            t_field($table->string("teacher_mental_aura"),"教师气场描述");
            t_field($table->integer("teacher_mental_aura_score"),"教师气场评分");
            t_field($table->string("teacher_exp"),"教师经验描述");
            t_field($table->integer("teacher_exp_score"),"教师经验评分");
            t_field($table->string("teacher_point_explanation"),"知识点讲解描述");
            t_field($table->integer("teacher_point_explanation_score"),"知识点讲解评分");
            t_field($table->string("teacher_class_atm"),"课堂氛围描述");
            t_field($table->integer("teacher_class_atm_score"),"课堂氛围评分");
            t_field($table->string("teacher_method"),"讲题方法思路/英语发音,语音读音错误描述");
            t_field($table->integer("teacher_method_score"),"讲题方法思路/英语发音,语音读音错误评分");
            t_field($table->string("teacher_knw_point"),"知识点与练习比例描述");
            t_field($table->integer("teacher_knw_point_score"),"知识点与练习比例评分");
            t_field($table->string("teacher_dif_point"),"重难点把握描述");
            t_field($table->integer("teacher_dif_point_score"),"重难点把握评分");
            t_field($table->string("teacher_blackboard_writing"),"板书描述");
            t_field($table->integer("teacher_blackboard_writing_score"),"板书评分");
            t_field($table->string("teacher_explain_rhythm"),"讲解节奏描述");
            t_field($table->integer("teacher_explain_rhythm_score"),"讲解节奏评分");
            t_field($table->string("teacher_language_performance"),"语言表达组织能力描述");
            t_field($table->integer("teacher_language_performance_score"),"语言表达组织能力评分");
            t_field($table->string("teacher_operation"),"教师端操作描述");
            t_field($table->integer("teacher_operation_score"),"教师端操作评分");
            t_field($table->string("teacher_environment"),"周边环境描述");
            t_field($table->integer("teacher_environment_score"),"周边环境评分");
            t_field($table->integer("teacher_lecture_score"),"老师试讲总评分");




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

              
