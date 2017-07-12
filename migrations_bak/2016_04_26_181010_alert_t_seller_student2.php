<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTSellerStudent2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_seller_student_info', function (Blueprint $table)
        {
            $table->integer("st_application_time");
            $table->string("st_application_nick",64);
            $table->string("st_from_school",64);
            $table->string("st_demand",64);
            $table->string("st_test_paper",64);
            $table->integer("st_class_time");
            $table->integer("st_arrange_teacherid");
            $table->integer("st_scheduling_time");

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
            $table->dropColumn("st_application_time");
            $table->dropColumn("st_application_nick");
            $table->dropColumn("st_from_school");
            $table->dropColumn("st_demand");
            $table->dropColumn("st_test_paper");
            $table->dropColumn("st_class_time");
            $table->dropColumn("st_arrange_teacherid");
            $table->dropColumn("st_scheduling_time");
        });


    }
}
