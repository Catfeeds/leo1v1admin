<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TBookInfoAddIndex extends Migration
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
            $table->index("sys_opt_time" ,"sys_opt_time" );
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
            $table->dropIndex("sys_opt_time" );
        });

        //
    }
}
