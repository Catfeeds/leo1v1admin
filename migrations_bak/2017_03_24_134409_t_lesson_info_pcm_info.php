<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoPcmInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field($table->integer("pcm_file_count"),"pcm生成的文件数");
            t_field($table->integer("pcm_file_all_size"),"pcm生成的文件总大小");
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
