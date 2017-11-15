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
            // t_field($table->tinyInteger("confirm_flag"),"助教确认交接单成功 0:未设置 1:已确认");
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
