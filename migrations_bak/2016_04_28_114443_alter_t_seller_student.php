<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTSellerStudent extends Migration
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
            $table->dropColumn("st_arrange_teacherid");
            $table->dropColumn("st_scheduling_time");
            $table->integer("st_arrange_lessonid");
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
        Schema::table('t_seller_student_info', function (Blueprint $table)
        {

            $table->integer("st_arrange_teacherid");
            $table->integer("st_scheduling_time");
            $table->dropColumn("st_arrange_lessonid");
        });
        //
    }
}
