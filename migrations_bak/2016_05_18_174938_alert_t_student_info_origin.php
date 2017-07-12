<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTStudentInfoOrigin extends Migration
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
            $table->string("origin",20);


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
        Schema::table('t_seller_student_info', function( Blueprint $table)
        {
            $table->dropColumn("origin");
        });

    }
}
