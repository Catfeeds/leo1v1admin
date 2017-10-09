<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewFristSetAdminid extends Migration
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
                $table->integer("first_seller_adminid")
                ,"新例子的cc id" );


            \App\Helper\Utils::comment_field(
                $table->integer("tmk_set_seller_adminid")
                ,"tmk  设置的 cc id " );
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
