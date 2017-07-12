<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentInfoFirstMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::table('t_seller_student_info', function (Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->Integer('first_money') ,
                "首次金额"
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
        Schema::table('t_seller_student_info', function (Blueprint $table)
        {
            $table->dropColumn('first_money'); 
        });
        //
    }
}
