<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddInterviewScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("interview_score"),"第一科目面试得分");
            t_field($table->integer("second_interview_score"),"第二科目面试得分");
            t_field($table->integer("test_transfor_per"),"试听转化率");
            t_field($table->integer("week_liveness"),"一周活跃度");
        });

        Schema::create('t_teacher_label', function (Blueprint $table){
            $table->integer("id",true);
            t_field($table->integer("teacherid"),"");
            t_field($table->integer("label_type"),"标签类型");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("is_good_flag"),"好坏");
            t_field($table->integer("detail_type"),"详情说明");
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
