<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssWeeklyInfoAddRegisteredStudentList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_ass_weekly_info', function( Blueprint $table)
        {
            t_field($table->text("registered_student_list"),"在册学员名单");
            t_field($table->integer("registered_student_num"),"在册学生数量");
        });

        Schema::table('db_weiyi.t_month_ass_student_info', function( Blueprint $table)
        {
            t_field($table->text("performance_end_stu_list"),"当月结课未续费名单");
            t_field($table->text("first_lesson_stu_list"),"第一次课学生名单");
            t_field($table->integer("performance_cc_tran_num"),"cc转介绍数量,10号存档");
            t_field($table->integer("performance_cc_tran_money"),"cc转介绍金额,10号存档");
            t_field($table->integer("performance_cr_renew_num"),"cr续费数量,10号存档");
            t_field($table->integer("performance_cr_renew_money"),"cr续费金额,10号存档");
            t_field($table->integer("performance_cr_new_num"),"cc新签数量,10号存档");
            t_field($table->integer("performance_cr_new_money"),"cc新签金额,10号存档");                     
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
