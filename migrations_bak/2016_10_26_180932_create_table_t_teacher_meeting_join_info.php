<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTeacherMeetingJoinInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_teacher_meeting_join_info', function (Blueprint $table)
        {
            $table->integer("teacherid");
            $table->string("create_time",20);
            $table->integer("join_info");
 
            $table->primary(["teacherid", "create_time" ]);
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
