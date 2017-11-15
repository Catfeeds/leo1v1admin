<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentCcToCrAddRejectFlagAss extends Migration
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
            t_field($table->tinyInteger("reject_flag_ass"),"助教组长驳回组员 0:未驳回 1:已驳回");
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
