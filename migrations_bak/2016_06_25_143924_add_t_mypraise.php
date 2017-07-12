<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTMypraise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_mypraise',function(Blueprint $table){
            $table->integer("add_userid");
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
        Schema::table('t_mypraise',function(Blueprint $table){
            $table->dropColumn("add_userid");
        });
    }
}
