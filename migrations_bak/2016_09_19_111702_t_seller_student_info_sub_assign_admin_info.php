<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentInfoSubAssignAdminInfo extends Migration
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
            \App\Helper\Utils::comment_field(
                $table->integer("sub_assign_adminid")
                ,"下级分配者"
            );
            \App\Helper\Utils::comment_field(
                $table->integer("sub_assign_time")
                ,"下级分配时间"
            );

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
