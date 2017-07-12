<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFreeTimeAddNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_freetime_for_week', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->string("free_time_new",2048),
                "新版空闲时间" );
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
    }
}
