<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddInterviewAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_info', function (Blueprint $table) {
            \App\Helper\Utils::comment_field(
                $table->integer("second_subject"),"第二科目");
            \App\Helper\Utils::comment_field(
                $table->integer("third_subject"),"第三科目");
            \App\Helper\Utils::comment_field(
                $table->string("interview_access"),"面试评价");
            \App\Helper\Utils::comment_field(
                $table->string("tea_note"),"教务备注");
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
