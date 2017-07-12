<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTSellerStudentInfoForUserDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_seller_student_info', function( Blueprint $table)
        {

            $table->dropColumn( "desc" );
            $table->string("user_desc");
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
        Schema::table('t_seller_student_info', function( Blueprint $table)
        {
            $table->dropColumn( "user_desc" );
            $table->string("desc");
        });


        //
    }
}
