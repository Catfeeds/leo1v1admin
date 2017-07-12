<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddTeacherIsGoodFlag extends Migration
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
                $table->string("teacher_join_info",5000),"与会人信息");

        });
        Schema::table('t_teacher_info', function (Blueprint $table) {
            \App\Helper\Utils::comment_field(
                $table->integer("is_good_flag")->default(0),"是否优秀");

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
