<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTOrderLessonList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_order_lesson_list', function (Blueprint $table)
        {
            $table->integer('orderid');
            $table->integer('lessonid');
            $table->integer('lesson_count');
            $table->integer('per_price');
            $table->integer('price');

            $table->primary(["orderid","lessonid"]);
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
