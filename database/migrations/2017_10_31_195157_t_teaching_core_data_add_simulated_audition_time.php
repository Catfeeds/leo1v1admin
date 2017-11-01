<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeachingCoreDataAddSimulatedAuditionTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teaching_core_data', function( Blueprint $table)
        {

            $table->dropColumn('simulated audition_time');
            $table->dropColumn('simulated audition_num');
            t_field($table->integer("simulated_audition_num"),"模拟试听数");
            t_field($table->string("simulated_audition_time",32),"模拟试听时长");

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
