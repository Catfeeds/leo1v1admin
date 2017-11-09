<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewAddTmkLastRevisitTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_student_new', function( Blueprint $table)
        {
            t_field($table->integer("tmk_last_revisit_time"),"tmk最后一次回访时间");
        });

        Schema::table('db_weiyi.t_seller_student_new', function($table){
            $table->dropColumn('tmk_called_count'); 
            $table->dropColumn('tmk_no_called_count');
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
    }
}
