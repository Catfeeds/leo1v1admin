<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSummerWeekRegularCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_summer_week_regular_course', function (Blueprint $table)
        {
            t_field($table->integer("teacherid"),"");
            t_field($table->integer("userid"),"");
            t_field($table->string("start_time",20),"");              
            t_field($table->string("end_time",20),"");              
            t_field($table->integer("lesson_count"),"");
            t_field($table->integer("competition_flag"),"");
 
            $table->primary(["teacherid", "start_time" ]);
            $table->index("userid"  );
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
