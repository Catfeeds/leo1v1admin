<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTMonthAssStudentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_month_ass_student_info', function (Blueprint $table){
            t_field($table->integer("adminid"),"助教adminid");
            t_field($table->integer("month"),"月度时间,以每月一日");
            t_field($table->integer("read_student"),"在读学员人数");
            t_field($table->integer("stop_student"),"结课学员人数");
            t_field($table->integer("month_stop_student"),"当月结课人数");
            t_field($table->integer("all_student"),"在册人数");
            t_field($table->integer("warning_student"),"预警学员人数");
            t_field($table->integer("renw_price"),"续费金额");
            t_field($table->integer("renw_student"),"续费人数");
            t_field($table->integer("tran_price"),"转介绍金额");
            t_field($table->integer("return_student"),"退费人数");
            t_field($table->integer("lesson_total"),"当月课时总量");
            t_field($table->string("lesson_ratio"),"课时系数");
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
