<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssWeeklyInfoAddNewStuNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_ass_weekly_info', function( Blueprint $table)
        {
            t_field($table->integer("new_stu_num"),"新签学生数量");            
            t_field($table->integer("end_stu_num"),"结课学生数量");            
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
