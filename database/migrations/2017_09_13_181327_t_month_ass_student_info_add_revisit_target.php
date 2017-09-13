<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthAssStudentInfoAddRevisitTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_month_ass_student_info', function( Blueprint $table)
        {
            t_field($table->integer("revisit_target"),"回访目标量");
            t_field($table->integer("revisit_real"),"实际回访量");
            t_field($table->integer("first_revisit_num"),"首次回访量");
            t_field($table->integer("un_first_revisit_num"),"未首次回访量");
            t_field($table->integer("refund_score"),"退费扣分值*100");
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
