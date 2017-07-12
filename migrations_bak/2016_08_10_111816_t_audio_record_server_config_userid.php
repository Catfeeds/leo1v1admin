<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAudioRecordServerConfigUserid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_audio_record_server', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("config_userid")->nullable(),
                "登录声网所用的userid" );
            $table->unique( "config_userid" );
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
