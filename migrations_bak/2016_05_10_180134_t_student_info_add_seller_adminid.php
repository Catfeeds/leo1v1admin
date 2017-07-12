<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAddSellerAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('t_student_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field( $table->integer("seller_adminid"),"销售 adminid" );
        });

        //\App\Helper\Utils::comment_field( $table->integer("confirm_flag"),"课时确认   0:未确认,1:有效课程 2:无效课程," );
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_student_info', function( Blueprint $table)
        {
            $table->dropColumn("seller_adminid");
        });
        //
    }
}
