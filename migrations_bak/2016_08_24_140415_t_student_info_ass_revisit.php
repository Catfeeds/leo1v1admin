<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAssRevisit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_student_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("ass_revisit_last_week_time"),
                "周回访时间");
            \App\Helper\Utils::comment_field(
                $table->integer("ass_revisit_last_month_time"),
                "月回访时间");

        });

        //
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
