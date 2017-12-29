<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoResetZipUrl extends Migration
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
            // $table->dropColumn('zip_url');
            // t_field($table->string("zip_url",100) ,"老师讲义压缩包链接");
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
