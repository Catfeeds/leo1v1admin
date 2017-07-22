<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewTmkSetSellerTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('db_weiyi.t_seller_student_new', function( Blueprint $table)
        {
            t_field($table->integer("tmk_set_seller_time"),"tmk有效转到cc时间");
            $table->index("tmk_set_seller_time");
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
        Schema::table('db_weiyi.t_seller_student_new', function( Blueprint $table)
        {
            $table->dropColumn("tmk_set_seller_time");
        });


        //
    }
}
