<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTWinterHolidayWeekRegularCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_winter_week_regular_course', function (Blueprint $table)
        {
            $table->integer("teacherid");
            $table->integer("userid");
            $table->string("start_time",20);
            $table->string("end_time",20);
            $table->integer("lesson_count");
 
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
