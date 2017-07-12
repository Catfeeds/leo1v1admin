<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentInfoNotifyLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_seller_student_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("notify_lesson_day1")
                ,"第一次通知上课时间" );
            \App\Helper\Utils::comment_field(
                $table->integer("notify_lesson_day2")
                ,"第二次通知上课时间" );

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
