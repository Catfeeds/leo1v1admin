<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentInfoAddPhoneLocation extends Migration
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
            $table->string("phone_location");
            $table->integer("next_revisit_time");
            $table->index(["admin_revisiterid", "next_revisit_time"], "admin_revisiterid__next_revisit_time" );
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
            $table->dropColumn ("phone_location");
            $table->dropColumn ("next_revisit_time");
            $table->dropIndex("admin_revisiterid__next_revisit_time");

        });
        //
    }
}
