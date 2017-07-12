<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentInfoCancelReason extends Migration
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
                $table->string("cancel_reason")
                ,"取消原因"
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
