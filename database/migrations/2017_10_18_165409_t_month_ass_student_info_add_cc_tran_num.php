<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthAssStudentInfoAddCcTranNum extends Migration
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
            t_field($table->integer("cc_tran_num"),"cc签单助教转介绍数");
            t_field($table->integer("cc_tran_money"),"cc签单助教转介绍金额");
            t_field($table->integer("hand_tran_num"),"手动填写转介绍数量");
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
