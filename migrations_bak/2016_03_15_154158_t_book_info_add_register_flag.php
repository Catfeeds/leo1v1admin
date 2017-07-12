<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TBookInfoAddRegisterFlag extends Migration
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
            $table->integer("register_flag");
            $table->index([ "register_flag",  "book_time"],  "register_flag__book_time"  );
            $table->index([ "register_flag",  "book_time_next"],  "register_flag__book_time_next"  );
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
            $table->dropColumn ("register_flag");
            $table->dropIndex("register_flag__book_time");
            $table->dropIndex("register_flag__book_time_next");
        });
        //
    }
}
