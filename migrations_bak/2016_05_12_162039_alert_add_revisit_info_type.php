<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertAddRevisitInfoType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_revisit_info', function( Blueprint $table)
        {
            $table->integer("revisit_type");
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
        Schema::table('t_revisit_info', function( Blueprint $table)
        {
            $table->dropColumn("revisit_type");
        });

 
    }
}
