<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoFromType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('t_order_info', function (Blueprint $table)
        {
            $table->Integer('stu_from_type');      
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
        Schema::table('t_order_info', function (Blueprint $table)
        {
            $table->dropColumn("stu_from_type");
        });


    }
}
