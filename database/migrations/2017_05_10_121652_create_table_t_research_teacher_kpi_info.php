<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTResearchTeacherKpiInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_research_teacher_kpi_info', function (Blueprint $table){
            t_field($table->integer("kid"),"uid/subject");
            t_field($table->integer("month"),"月度时间,以每月一日");
            t_field($table->integer("type_flag"),"类型 1个人,2学科");
            t_field($table->string("name"),"用户名/学科名");
            t_field($table->string("interview_time",20),"审核时长");
            t_field($table->integer("interview_lesson"),"面试试听课数");
            t_field($table->integer("interview_order"),"面试签单数");
            t_field($table->string("interview_per",20),"面试签单率");
            t_field($table->string("record_time",20),"新入职反馈时长");
            t_field($table->integer("record_num"),"反馈数量");
            t_field($table->integer("first_lesson"),"首次试听课数");
            t_field($table->integer("first_order"),"首次试听签单数");
            t_field($table->string("first_per",20),"首次试听签单率");
            t_field($table->string("first_next_per",20),"反馈前签单率");
            t_field($table->string("next_per",20),"反馈后签单率");
            t_field($table->string("add_per",20),"反馈后提升度");
            t_field($table->string("other_record_time",20),"投诉处理时长");
            t_field($table->integer("lesson_num"),"试听课数(销售)");
            t_field($table->string("lesson_per",20),"签单率");
            t_field($table->string("lesson_num_per",20),"试听课数(销售)-占比");
            t_field($table->string("lesson_per_other",20),"签单率(转介绍)");
            t_field($table->string("lesson_per_kk",20),"签单率(扩课)");
            t_field($table->string("lesson_per_change",20),"签单率(换老师)");
            t_field($table->integer("interview_time_score"),"审核时长得分");
            t_field($table->integer("interview_per_score"),"面试签单率得分");
            t_field($table->integer("record_time_score"),"反馈时长得分");
            t_field($table->integer("record_num_score"),"反馈数量得分");
            t_field($table->integer("first_per_score"),"首次签单率得分");
            t_field($table->integer("add_per_score"),"反馈后提升度得分");
            t_field($table->integer("other_record_time_score"),"投诉处理时长得分");
            t_field($table->integer("lesson_num_per_score"),"试听课数(销售)-占比得分");
            t_field($table->integer("lesson_per_score"),"签单率得分");
            t_field($table->integer("lesson_per_other_score"),"签单率(转介绍)得分");
            t_field($table->integer("lesson_per_kk_score"),"签单率(扩课)得分");
            t_field($table->integer("lesson_per_change_score"),"签单率(换老师)得分");
            t_field($table->integer("total_score"),"总得分");

            $table->primary(["kid","month"]);
            $table->index("type_flag","type_flag");
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
