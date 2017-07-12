<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTBookInfoAddQq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_book_info', function( Blueprint $table)
        {
            $table->string("qq");
        });


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
            $table->dropColumn("qq");
        });


        //
    }
}
