<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCrWeekMonthInfoAveragePersonEffect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_cr_week_month_info', function( Blueprint $table)
        {
            t_field($table->integer("average_person_effect"),"平均人效(非入职完整月)");
            t_field($table->integer("cumulative_refund_rate"),"合同累计退费率");
            t_field($table->integer("stop_student"),"停课学生");
            t_field($table->integer("drop_student"),"休学学员");
            t_field($table->integer("summer_winter_stop_student"),"寒暑假停课");
            t_field($table->integer("new_order_assign_num"),"新签合同未排量(已分配)");
            t_field($table->integer("new_order_unassign_num"),"新签合同未排量(未分配)");
            t_field($table->integer("student_end_per"),"结课率");
            t_field($table->integer("new_student_num"),"本月新签学生数");
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
