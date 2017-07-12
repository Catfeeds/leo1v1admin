<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTestLessonOrderInfoOld extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_test_lesson_order_info_old', function (Blueprint $table)
        {         
            $table->integer("teacherid");
            $table->integer("userid");
            $table->integer("first_lesson_time");
            $table->integer("test_lesson_time");
            $table->primary(["teacherid","userid"]);
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
