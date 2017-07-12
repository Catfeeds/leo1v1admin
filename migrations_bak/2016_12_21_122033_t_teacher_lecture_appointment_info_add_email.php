<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TTeacherLectureAppointmentInfoAddEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_lecture_appointment_info', function ($table) {
            $table->dropColumn('emial');
        });
        Schema::table('t_teacher_lecture_appointment_info', function( Blueprint $table)
        {
            add_field($table->string("email"),"邮箱" );
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
