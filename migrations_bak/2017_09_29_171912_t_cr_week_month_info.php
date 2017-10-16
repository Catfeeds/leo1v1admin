<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCrWeekMonthInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_cr_week_month_info', function (Blueprint $table){
              t_field($table->integer("id",true),"本表记录助教周报月报存档信息");
              t_field($table->integer("create_time"),"存档时间");
              t_field($table->string("create_time_range"),"存档时间范围");
              t_field($table->integer("type"),"存档类型1月报2周报3跨月周报4漏斗类型");

              t_field($table->integer("target"),"月度目标收入");
              t_field($table->integer("total_price"),"完成金额");
              t_field($table->integer("kpi_per"),"完成率");
              t_field($table->integer("gap_money"),"缺口金额");

              t_field($table->integer("total_income"),"现金总收入");
              t_field($table->integer("person_num"),"下单总人数");
              t_field($table->integer("total_price_thirty"),"入职完整月人员签单额");
              t_field($table->integer("person_num_thirty"),"入职完整月人员人数");
              t_field($table->integer("person_num_thirty_per"),"平均人效");
              t_field($table->integer("contract_per"),"平均单笔");
              t_field($table->integer("month_kpi_per"),"月KPI完整率");
              t_field($table->integer("cr_num"),"CR总人数");
              t_field($table->integer("finish_num"),"结课学员数");
              t_field($table->integer("refund_num"),"退费总人数");



              t_field($table->integer("lesson_target"),"课时系数目标量");
              t_field($table->integer("read_num"),"在读学生数量");
              t_field($table->integer("total_student"),"上课学生数量");
              t_field($table->integer("lesson_consume_target"),"课时消耗目标数量");
              t_field($table->integer("lesson_consume"),"课时消耗实际数量");
              t_field($table->integer("teacher_leave"),"老师请假课时");
              t_field($table->integer("student_leave"),"学生请假课时");
              t_field($table->integer("other_leave"),"其他原因未上课时");
              t_field($table->integer("lesson_complete_per"),"课时完成率");
              t_field($table->integer("student_arrive"),"学生到课数量");
              t_field($table->integer("lesson_plan"),"排课数量");
              t_field($table->integer("student_arrive_per"),"学生到课率");
              t_field($table->integer("lesson_income"),"课时收入");

              t_field($table->integer("expect_finish_num"),"预计结课学生数量");
              t_field($table->integer("plan_renew_num"),"计划内续费学生数量");
              t_field($table->integer("other_renew_num"),"计划外续费学生数量");
              t_field($table->integer("real_renew_num"),"实际续费学生数量");
              t_field($table->integer("total_renew"),"续费金额");
              t_field($table->integer("renew_num_per"),"续费平均单笔");
              t_field($table->integer("renew_per"),"月续费率");
              t_field($table->integer("finish_renew_per"),"月预警续费率");


              t_field($table->integer("tranfer_phone_num"),"转介绍至CC例子量");
              t_field($table->integer("tranfer_total_price"),"转介绍至CC例子签单量");
              t_field($table->integer("tranfer_total_num"),"转介绍至CC例子签单金额");
              t_field($table->integer("tranfer_success_per"),"月转介绍至CC签单率");
              t_field($table->integer("tranfer_num"),"转介绍成单数量");
              t_field($table->integer("total_tranfer"),"转介绍总金额");
              t_field($table->integer("tranfer_num_per"),"转介绍平均单笔");

              t_field($table->integer("total_test_lesson_num"),"扩课试听数量");
              t_field($table->integer("success_num"),"扩课成单数量");
              t_field($table->integer("wait_num"),"扩科待跟进数量");
              t_field($table->integer("fail_num"),"扩科未成单数量");
              t_field($table->integer("kk_success_per"),"月扩课成功率");

              t_field($table->integer("parent_complaint_num"),"家长投诉数量");
              t_field($table->integer("abnormal_refund_num"),"非正常退费事件数量");
              t_field($table->integer("abnormal_refund_money"),"非正常退费金额");
              t_field($table->integer("force_refund_num"),"不可抗力退费数量");
              t_field($table->integer("force_refund_money"),"不可抗力退费金额");
              t_field($table->integer("total_refund_money"),"退费总额");

              $table->index("create_time");
              $table->index("type");
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
