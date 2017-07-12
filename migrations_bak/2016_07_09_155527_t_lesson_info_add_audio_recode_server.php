<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddAudioRecodeServer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->string("record_audio_server1"),
                "声音记录服务器1");
            \App\Helper\Utils::comment_field(
                $table->string("record_audio_server2"),
                "声音记录服务器2");

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
