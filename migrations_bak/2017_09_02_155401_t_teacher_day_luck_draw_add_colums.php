<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherDayLuckDrawAddColums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_day_luck_draw', function( Blueprint $table)
        {
            t_field($table->integer("presenter_id"),"发奖人id");
            $table->index(["teacherid"]);
            $table->index(["presenter_id"]);
            $table->index(["do_time"]);

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
