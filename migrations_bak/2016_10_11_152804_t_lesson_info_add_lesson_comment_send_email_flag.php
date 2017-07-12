<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddLessonCommentSendEmailFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_lesson_info',function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("lesson_comment_send_email_flag")->default(0),"0 未发送课堂反馈, 1 已发送");
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
