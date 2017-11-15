<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentCcToCrDropcolum extends Migration
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
            $table->dropColumn('reject_flag_ass_time');
            $table->dropColumn('reject_flag_ass');
            $table->dropColumn('reject_master');
            $table->dropColumn('reject_ass');
            $table->dropColumn('confirm_flag');
            //
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
