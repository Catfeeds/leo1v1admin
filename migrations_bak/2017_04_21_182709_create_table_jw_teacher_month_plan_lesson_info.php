<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableJwTeacherMonthPlanLessonInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_jw_teacher_month_plan_lesson_info', function (Blueprint $table){
            t_field($table->integer("adminid"),"教务adminid");
            t_field($table->integer("month"),"月度时间,以每月一日");
            t_field($table->integer("all_plan"),"总排课量");
            t_field($table->integer("all_plan_done"),"已排课程");
            t_field($table->integer("un_plan"),"待排量");
            t_field($table->integer("gz_count"),"挂载量");
            t_field($table->integer("back_count"),"退回量");
            t_field($table->string("plan_per"),"排课完成率");
            t_field($table->integer("tran_count"),"排课转化量");
            t_field($table->integer("tran_count_seller"),"排课转化量(销售)");
            t_field($table->integer("tran_count_ass"),"排课转化量(助教)");
            t_field($table->string("tran_per"),"排课转化率");
            $table->primary(["adminid","month"]);
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
