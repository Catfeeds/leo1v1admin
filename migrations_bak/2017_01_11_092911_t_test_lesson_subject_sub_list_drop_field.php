<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectSubListDropField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_test_lesson_subject_sub_list', function( Blueprint $table)
        {
            $table->dropColumn(['courseware_flag', 'courseware_flag_score', 'lesson_preparation_content','lesson_preparation_content_score','courseware_quality','courseware_quality_score','tea_process_design','tea_process_design_score','class_atm','class_atm_score','tea_method','tea_method_score','knw_point','knw_point_score','dif_point','dif_point_score','teacher_blackboard_writing','teacher_blackboard_writing_score','tea_rhythm','tea_rhythm_score','content_fam_degree','content_fam_degree_score','answer_question_cre','answer_question_cre_score','language_performance','language_performance_score','tea_attitude','tea_attitude_score','tea_concentration','tea_concentration_score','tea_accident','tea_accident_score','tea_operation','tea_operation_score','tea_environment','tea_environment_score','class_abnormality','class_abnormality_score','test_lesson_score','test_lesson_advice','test_lesson_comment_time']);           
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
