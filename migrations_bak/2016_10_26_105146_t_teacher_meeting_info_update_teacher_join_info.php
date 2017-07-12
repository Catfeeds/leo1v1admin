<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherMeetingInfoUpdateTeacherJoinInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_meeting_info', function (Blueprint $table) {
            $table->dropColumn('teacher_join_info');
        });
        Schema::table('t_teacher_meeting_info', function (Blueprint $table) {
            \App\Helper\Utils::comment_field(
                $table->string("teacher_join_info",15000),"与会人信息");

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
