<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOriginKeyAddIndexOriginLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_origin_key', function( Blueprint $table)
        {
            $table->index("origin_level");
        });

        Schema::table('db_weiyi.t_student_info', function( Blueprint $table)
        {
            $table->index("origin_level");
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
    }
}
