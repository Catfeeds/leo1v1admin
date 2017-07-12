<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAddOriginUserid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_student_info', function( Blueprint $table)
        {
            $table->integer("origin_userid"); 
        });

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
            $table->dropColumn("origin_userid");
        });
        //
    }
}
