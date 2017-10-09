<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherAssessmentListCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_fulltime_teacher_assessment_list', function (Blueprint $table){
            t_field($table->integer("adminid"),"");
            t_field($table->integer("add_time"),"提交时间");
            t_field($table->integer("modify_time"),"修改时间");
            t_field($table->integer("assess_time"),"考评时间");
            t_field($table->integer("assess_adminid"),"考评人");
            t_field($table->integer("observe_law_score"),"遵纪守法得分");
            t_field($table->integer("core_socialist_score"),"价值观得分");
            t_field($table->integer("work_responsibility_score"),"工作责任得分");
            t_field($table->integer("obey_leadership_score"),"服从安排得分");
            t_field($table->integer("dedication_score"),"爱岗敬业得分");
            t_field($table->integer("prepare_lesson_score"),"备课得分");
            t_field($table->integer("upload_handouts_score"),"上传讲义得分");
            t_field($table->integer("handout_writing_score"),"讲义编写得分");
            t_field($table->integer("no_absences_score"),"遵照课表上课得分");
            t_field($table->integer("late_leave_score"),"不迟到早退得分");
            t_field($table->integer("prepare_quality_score"),"备课质量得分");
            t_field($table->integer("class_concent_score"),"上课专注得分");
            t_field($table->integer("tea_attitude_score"),"教学态度得分");
            t_field($table->integer("after_feedback_score"),"课后反馈得分");
            t_field($table->integer("modify_homework_score"),"修改作业得分");
            t_field($table->integer("teamwork_positive_score"),"配合试听得分");
            t_field($table->integer("test_lesson_prepare_score"),"试听备课得分");
            t_field($table->integer("undertake_actively_score"),"承担组长分配任务得分");
            t_field($table->integer("active_share_score"),"积极分享得分");
            t_field($table->integer("order_per_score"),"试听转化率得分");
            t_field($table->integer("stu_num_score"),"常规学生数得分");
            t_field($table->integer("lesson_level_score"),"家长评星得分");
            t_field($table->integer("stu_lesson_total_score"),"周课时数得分");
            t_field($table->integer("complaint_refund_score"),"投诉退费得分");
            t_field($table->integer("moral_education_score"),"德育自评得分");
            t_field($table->integer("tea_score"),"教学自评得分");
            t_field($table->integer("teach_research_score"),"教研自评得分");
            t_field($table->integer("result_score"),"成果自评得分");
            t_field($table->integer("total_score"),"自评总分得分");
            t_field($table->integer("rate_stars"),"自评星级");
            t_field($table->integer("moral_education_score_maste"),"德育考评得分");
            t_field($table->integer("tea_score_master"),"教学考评得分");
            t_field($table->integer("teach_research_score_master"),"教研考评得分");
            t_field($table->integer("result_score_naster"),"成果考评得分");
            t_field($table->integer("total_score_master"),"考评总分得分");
            t_field($table->integer("rate_stars_master"),"考评星级");

            $table->primary("adminid");
            $table->index("add_time","add_time");
            $table->index("modify_time","modify_time");
            $table->index("assess_time","assess_time");
            $table->index("assess_adminid","assess_adminid");

           
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
