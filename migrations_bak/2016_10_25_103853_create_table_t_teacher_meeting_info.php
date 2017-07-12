<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTeacherMeetingInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_teacher_meeting_info', function (Blueprint $table)
        {
            $table->Integer('id',true);
            $table->Integer('create_time');
            $table->string('summary');
            $table->string('theme');
            $table->string('moderator',20);
            $table->string('address');
            $table->index("id");
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
