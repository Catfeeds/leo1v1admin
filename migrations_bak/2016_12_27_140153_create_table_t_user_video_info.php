<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTUserVideoInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_user_video_info', function (Blueprint $table)
        {
            $table->integer("userid");
            $table->integer("lessonid");
            $table->integer("time");
 
            $table->primary(["userid", "lessonid","time"]);
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
