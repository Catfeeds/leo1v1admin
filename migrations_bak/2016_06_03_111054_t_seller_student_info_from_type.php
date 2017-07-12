<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentInfoFromType extends Migration
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
            $table->Integer('from_type');      
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
            $table->dropColumn("from_type");
        });
    }
}
