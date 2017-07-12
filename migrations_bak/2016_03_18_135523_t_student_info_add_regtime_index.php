<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAddRegtimeIndex extends Migration
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
            $table->index("reg_time" ,"reg_time" );
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
            $table->dropIndex("reg_time" );
        });
        //
    }
}
