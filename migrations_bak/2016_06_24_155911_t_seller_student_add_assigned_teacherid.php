<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentAddAssignedTeacherid extends Migration
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
            \App\Helper\Utils::comment_field(
                $table->Integer('assigned_teacherid') ,
                "派单抢到者id"
            );
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
