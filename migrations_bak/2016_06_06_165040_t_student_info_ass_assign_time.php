<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAssAssignTime extends Migration
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
                $table->integer("ass_assign_time"),
                "助教分配时间" );
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
        Schema::table('t_student_info', function( Blueprint $table)
        {
            $table->dropColumn("ass_assign_time");
        });
        //
    }
}
