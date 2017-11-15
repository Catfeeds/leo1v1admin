<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentCcToCrAddReject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_student_cc_to_cr', function( Blueprint $table)
        {
            t_field($table->integer("reject_flag_ass_time"),"助教驳回时间");
            t_field($table->integer("reject_master"),"(驳回人)助教组长 ");
            t_field($table->integer("reject_ass"),"(驳回人)助教组员 ");
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
