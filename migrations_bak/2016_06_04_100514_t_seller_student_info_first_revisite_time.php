<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentInfoFirstRevisiteTime extends Migration
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
                $table->Integer('first_revisite_time') ,
                "首次回访时间"
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
        Schema::table('t_seller_student_info', function (Blueprint $table)
        {
            $table->dropColumn('first_revisite_time'); 
        });

    }
}
