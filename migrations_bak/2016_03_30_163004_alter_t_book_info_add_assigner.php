<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTBookInfoAddAssigner extends Migration
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
            $table->string("assigner");
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
            $table->dropColumn ("assigner");
        });
        //
    }
}
