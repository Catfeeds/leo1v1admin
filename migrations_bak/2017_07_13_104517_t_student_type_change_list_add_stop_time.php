<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentTypeChangeListAddStopTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_student_type_change_list', function( Blueprint $table)
        {
            t_field($table->integer("recover_time"),"复课时间");
            t_field($table->string("stop_duration",64),"时长");
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
