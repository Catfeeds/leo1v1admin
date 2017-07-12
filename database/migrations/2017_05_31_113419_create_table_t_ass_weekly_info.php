<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAssWeeklyInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_ass_weekly_info', function (Blueprint $table){
            t_field($table->integer("adminid"),"adminid");
            t_field($table->integer("week"),"周时间,以每周第一天");
            t_field($table->integer("warning_student"),"预计结课学员数量");
            t_field($table->string("warning_student_list",500),"预计结课学员名单");
            t_field($table->integer("end_class_student"),"实际结课学员");
            t_field($table->integer("renw_student"),"续费人数");
            t_field($table->integer("renw_student_in_plan"),"计划内续费人数");
            t_field($table->integer("renw_price"),"续费金额");
            t_field($table->integer("tran_price"),"转介绍金额");
            t_field($table->integer("tran_require_count"),"转介绍量");
            t_field($table->integer("tran_require_succ"),"转介绍成功量");
            t_field($table->integer("kk_all"),"扩课申请量");
            t_field($table->integer("kk_succ"),"扩课成功");
            t_field($table->integer("kk_fail"),"扩课失败");
            t_field($table->integer("read_student"),"在读人数");
            t_field($table->integer("lesson_student"),"上课人数");
            t_field($table->integer("lesson_count"),"实际完成课时量");
            t_field($table->string("lesson_ratio",20),"实际课时系数");
            t_field($table->integer("tea_leave_lesson_count"),"老师请假课程课时量");
            t_field($table->integer("stu_leave_lesson_count"),"学生请假课程课时量");
            t_field($table->integer("other_lesson_count"),"其他情况课时量");
            t_field($table->string("lesson_per",20),"到课率");
            t_field($table->integer("complain_num"),"投诉量");
            t_field($table->integer("improper_refund_num"),"非正常退费数量");
            t_field($table->integer("improper_refund_money"),"非正常退费金额");
            t_field($table->integer("force_refund_num"),"不可抗力退费数量");
            t_field($table->integer("force_refund_money"),"不可抗力退费金额");
            t_field($table->integer("refund_money"),"退费总金额");
            $table->primary(["adminid","week"]);
        });

        Schema::table('db_weiyi.t_change_teacher_list', function( Blueprint $table)
        {
            t_field($table->integer("id_done_flag"),"完成标志 0 未设置,1已解决,2未解决");
            t_field($table->integer("done_time"),"完成时间");
            t_field($table->integer("is_resubmit_flag"),"是否重新提交申请");
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
