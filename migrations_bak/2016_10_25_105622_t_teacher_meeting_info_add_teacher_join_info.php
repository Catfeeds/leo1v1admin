<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherMeetingInfoAddTeacherJoinInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_meeting_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->string("teacher_join_info"),"与会老师信息");
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
