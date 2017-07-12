<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTSellerStudentInfoAddQq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        //
        Schema::table('t_seller_student_info', function( Blueprint $table)
        {
            $table->integer("trial_type");
            $table->string("qq",20);
        });


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
            $table->dropColumn("trial_type");
            $table->dropColumn("qq");
        });

        //
    }
}
