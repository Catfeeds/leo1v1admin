<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema ;

class AlterTUserBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_book_info', function( Blueprint $table)
        {
            
            $table->string("sys_operator");
            $table->integer("sys_opt_time",false, true);
            $table->string("phone_location");
                
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
        Schema::table('t_book_info', function( Blueprint $table)
        {
            $table->dropColumn ("sys_opt_time");
            $table->dropColumn ("sys_operator");
            $table->dropColumn ("phone_location");
        });
        //
    }
}
