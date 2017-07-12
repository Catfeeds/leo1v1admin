<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTAssistantInfoEName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_assistant_info', function( Blueprint $table)
        {
            $table->string("e_name");
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
        Schema::table('t_assistant_info', function( Blueprint $table)
        {
            $table->dropColumn("e_name");
        });



    }
}
