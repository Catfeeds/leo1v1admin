<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewNewHistoryCallInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('t_seller_student_new', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("competition_call_adminid")
                ,"抢用户的adminid" );

            \App\Helper\Utils::comment_field(
                $table->integer("competition_call_time")
                 ,"抢用户的时间" );

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
        //
    }
}
